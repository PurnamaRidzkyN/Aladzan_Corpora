<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PaymentController extends Controller
{
    public function chooseAddress(Request $request)
    {
        $request->validate(
            [
                'items_json' => ['required', 'json'],
            ],
            [
                'items_json.required' => 'Silakan pilih minimal 1 item.',
                'items_json.json' => 'Data tidak valid. Silakan coba lagi.',
            ],
        );
        $items = json_decode($request->input('items_json'), true);

        if (!$items || count($items) == 0) {
            return back()->with('error', 'Silakan pilih minimal satu item untuk checkout.');
        }

        $cartItemIds = collect($items)->pluck('id');

        foreach ($items as $item) {
            $existingCart = Cart::where('reseller_id', auth()->id())
                ->where('id', $item['id'])
                ->first();
            if ($existingCart) {
                // Kalau sudah ada, update qty
                $existingCart->quantity = $item['qty'];
                $existingCart->save();
            }
        }
        $addresses = auth()->user()->addresses()->get();
        $chooseeAddress = true;
        return view('store.profile.address', compact('cartItemIds', 'addresses', 'chooseeAddress','items'));
    }
    public function checkout(Request $request)
    {
        $request->validate(
            [
                'items_json' => ['required', 'json'],
                'address_id' => 'required|exists:addresses,id',
            ],
            [
                'items_json.required' => 'Silakan pilih minimal 1 item.',
                'items_json.json' => 'Data tidak valid. Silakan coba lagi.',
                'address_id.required' => 'Alamat tidak ditemukan.',
            ],
        );
        $items = json_decode($request->input('items_json'), true);

        // Ambil item dari keranjang
        $cartItems = Cart::where('reseller_id', auth()->id())
            ->whereIn('id', $items)
            ->with('variant.product.shop')
            ->get()
            ->groupBy(function ($item) {
                return $item->variant->product->shop->name;
            });

        // Ambil alamat tujuan reseller (user)
        $address = Address::where('reseller_id', auth()->id())->first();
        if (!$address) {
            return redirect()->route('cart')->with('error', 'Alamat belum diatur.');
        }

        $destination = $address->sub_district_id; // ini yang jadi tujuan ongkir

        $shopSubtotals = [];
        $ongkirPerShop = [];
        $totalOngkir = 0;

        foreach ($cartItems as $shopName => $itemsPerShop) {
            $subtotal = 0;
            $totalWeight = 0;

            // Hitung subtotal dan total berat dari toko ini
            foreach ($itemsPerShop as $item) {
                $subtotal += $item->variant->price * $item->quantity;
                $totalWeight += $item->variant->product->weight * $item->quantity;
            }

            $shopSubtotals[$shopName] = $subtotal;

            $shop = $itemsPerShop->first()->variant->product->shop;
            $origin = $shop->sub_district_id;

            $ongkir = $this->calculateShipping($origin, $destination, $totalWeight, $shopName);

            $ongkirPerShop[$shopName] = $ongkir;
            $totalOngkir += $ongkir;
        }

        $subtotalAll = array_sum($shopSubtotals);
        $total = $subtotalAll + $totalOngkir;
        return view('store.profile.checkout', compact('cartItems', 'shopSubtotals', 'totalOngkir', 'subtotalAll', 'total', 'ongkirPerShop', 'address'));
    }
    public function checkoutConfirm(Request $request)
    {
        try {
            $validated = $request->validate([
                'cart_ids' => 'required|array',
                'total_price' => 'required|numeric',
                'total_shipping' => 'required|numeric',
                'address_id' => 'required|exists:addresses,id',
                'payment_method' => 'required|numeric',
                'note' => 'nullable|string',
            ]);

            $address = Address::where('reseller_id', auth()->id())
                ->where('id', $request->address_id)
                ->first();

            if (!$address) {
                return redirect()
                    ->route('cart')
                    ->withErrors(['address_id' => 'Alamat tidak ditemukan'])
                    ->withInput();
            }

            DB::beginTransaction();

            $shipping_address_parts = [$address->recipient_name, 'Telp: ' . $address->phone_number, $address->address_detail, $address->village ? 'Kampung ' . $address->village : null, $address->neighborhood && $address->hamlet ? 'RT ' . $address->neighborhood . ' / RW ' . $address->hamlet : null, $address->sub_district ? 'Desa/Kel. ' . $address->sub_district : null, $address->district ? 'Kec. ' . $address->district : null, $address->city, $address->province . ' ' . $address->postal_code];

            $shipping_address = implode("\n", array_filter($shipping_address_parts));

            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'reseller_id' => auth()->id(),
                'total_price' => $validated['total_price'],
                'shipping_address' => $shipping_address,
                'total_shipping' => $validated['total_shipping'],
                'note' => $validated['note'],
            ]);

            $carts = Cart::with('variant.product')
                ->where('reseller_id', auth()->id())
                ->whereIn('id', $validated['cart_ids'])
                ->get();

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $cart->variant->id,
                    'product_name' => $cart->variant->product->name . ' - ' . $cart->variant->name,
                    'quantity' => $cart->quantity,
                    'price_each' => $cart->variant->price,
                ]);

                $cart->delete();
            }

            DB::commit();

            return redirect()->route('payment', $order->order_code);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->route('cart')->withErrors($e->validator)->withInput();
        }
    }
    public function payment($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();
        $total = $order->total_price + $order->total_shipping;
        if (!$order || !$order->reseller_id == auth()->id()) {
            return redirect()->back()->with('error', 'order tidak ditemukan atau tidak berlaku untuk Anda.');
        }
        $methods = [
            'Bank' => [['id' => 'bca', 'name' => 'BCA Virtual Account', 'type' => 'va', 'description' => 'Transfer ke rekening virtual BCA', 'va_number' => '123 456 7890'], ['id' => 'bni', 'name' => 'BNI Virtual Account', 'type' => 'va', 'description' => 'Transfer ke rekening virtual BNI', 'va_number' => '123 456 7890'], ['id' => 'bri', 'name' => 'BRI Virtual Account', 'type' => 'va', 'description' => 'Transfer ke rekening virtual BRI', 'va_number' => '123 456 7890'], ['id' => 'mandiri', 'name' => 'Mandiri Virtual Account', 'type' => 'va', 'description' => 'Transfer ke rekening virtual Mandiri', 'va_number' => '123 456 7890'], ['id' => 'permata', 'name' => 'Permata Virtual Account', 'type' => 'va', 'description' => 'Transfer ke rekening virtual Permata', 'va_number' => '123 456 7890']],
            'E-Wallet' => [['id' => 'dana', 'name' => 'DANA', 'type' => 'ewallet', 'description' => 'Bayar pakai DANA', 'phone_number' => '08123456789'], ['id' => 'ovo', 'name' => 'OVO', 'type' => 'ewallet', 'description' => 'Bayar pakai OVO', 'phone_number' => '08123456789'], ['id' => 'shopeepay', 'name' => 'ShopeePay', 'type' => 'ewallet', 'description' => 'Bayar pakai ShopeePay', 'phone_number' => '08123456789'], ['id' => 'linkaja', 'name' => 'LinkAja', 'type' => 'ewallet', 'description' => 'Bayar pakai LinkAja', 'phone_number' => '08123456789']],
            'QRIS' => [['id' => 'qris', 'name' => 'QRIS', 'type' => 'qris', 'description' => 'Scan QR dengan aplikasi apapun']],
        ];

        return view('store.profile.payment', compact('order', 'methods', 'total'));
    }
    public function paymentConfirm(Request $request)
    {
        $validated = $request->validate([
            'selected_method_id' => 'required|integer|exists:payment_methods,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048', // maksimal 2MB
        ]);
        $order = Order::where('order_code', $request->order_code)->first();
        if (!$order || !$order->reseller_id == auth()->id()) {
            return redirect()->back()->with('error', 'order tidak ditemukan atau tidak berlaku untuk Anda.');
        }
        $order->payment_method = $validated['selected_method_id'];

        $publicId = 'Pm/O-' . auth()->id() . '/' . $order->order_code;

        $payment = Cloudinary::uploadApi()->upload($request->file('bukti_pembayaran')->getRealPath(), [
            'public_id' => $publicId,
            'overwrite' => true,
            'resource_type' => 'image',
        ]);

        $order->update([
            'payment_proofs' => $payment['public_id'],
            'is_paid_at' => now(),
            'payment_method' => $validated['selected_method_id'],
        ]);
        return redirect()->route('order.history')->with('success', 'Bukti pembayaran sudah di kirimkan.');
    }

    private function calculateShipping($origin, $destination, $weight, $shopName)
    {
        $cachedOngkir = ShippingCost::where([['origin', $origin], ['destination', $destination], ['weight', $weight]])->first();

        if ($cachedOngkir) {
            return $cachedOngkir->cost;
        }

        $response = Http::asForm()
            ->withHeaders([
                'accept' => 'application/json',
                'key' => config('services.rajaongkir.key'),
            ])
            ->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => 'jne',
            ]);

        $data = $response->json();

        if (!isset($data['data']) || empty($data['data'])) {
            throw new \Exception('Gagal hitung ongkir untuk toko ' . $shopName);
        }

        $regularService = collect($data['data'])->firstWhere('service', 'REG');
        if (!$regularService) {
            throw new \Exception('Layanan JNE REG tidak tersedia untuk toko ' . $shopName);
        }

        $ongkir = $regularService['cost'] ?? 0;

        ShippingCost::create([
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'cost' => $ongkir,
        ]);

        return $ongkir;
    }
}
