<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Resi;
use App\Models\Order;
use App\Models\Address;
use App\Models\Setting;
use App\Models\OrderItem;
use App\Models\ResiSource;
use Illuminate\Support\Str;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Http;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

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
        return view('store.profile.address', compact('cartItemIds', 'addresses', 'chooseeAddress', 'items'));
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

        $address = Address::find($request->address_id);
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
                $itemWeight = $item->variant->product->weight;
                if ($itemWeight == 0) {
                    continue;
                }
                $totalWeight += $itemWeight * $item->quantity;
            }
            if ($itemWeight == 0) {
                $shopSubtotals[$shopName] = $subtotal;

                $ongkirPerShop[$shopName] = 0;
                $totalOngkir = 0;
                continue;
            }
            $shopSubtotals[$shopName] = $subtotal;

            $shop = $itemsPerShop->first()->variant->product->shop;
            $origin = $shop->sub_district_id;

            $ongkir = $this->calculateShipping($origin, $destination, $totalWeight, $shopName);

            $ongkirPerShop[$shopName] = $ongkir;
            $totalOngkir += $ongkir;
        }
        $resiSources = ResiSource::all();

        $subtotalAll = array_sum($shopSubtotals);
        $total = $subtotalAll + $totalOngkir;
        return view('store.profile.checkout', compact('resiSources', 'cartItems', 'shopSubtotals', 'totalOngkir', 'subtotalAll', 'total', 'ongkirPerShop', 'address'));
    }
    public function checkoutConfirm(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'cart_ids' => 'required|array',
                    'total_price' => 'required|numeric',
                    'total_shipping' => 'required|numeric',
                    'address_id' => 'required|exists:addresses,id',
                    'note' => 'nullable|string',
                    'has_resi' => 'required|string',
                    'resi_number' => 'required_if:has_resi,1|string|max:255',
                    'resi_file' => 'required_if:has_resi,1|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,|max:1048',
                    'resi_source_id' => 'required_if:has_resi,1|exists:resi_sources,id',
                ],
                [
                    'cart_ids.required' => 'Silakan pilih minimal 1 item.',
                    'cart_ids.array' => 'Data tidak valid. Silakan coba lagi.',
                    'address_id.required' => 'Alamat tidak ditemukan.',
                    'resi_number.required_if' => 'Nomor resi wajib diisi jika mengunggah resi.',
                    'resi_file.required_if' => 'File resi wajib diunggah jika mengunggah resi.',
                    'resi_file.mimes' => 'File resi harus berupa file dengan format: pdf, doc, docx, txt, jpg, jpeg, png.',
                    'resi_file.max' => 'Ukuran file resi maksimal 1MB.',
                    'resi_source_id.required_if' => 'Sumber resi wajib dipilih jika mengunggah resi.',
                    'resi_source_id.exists' => 'Sumber resi tidak valid.',
                ],
            );

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
            $resi = null;
            if ($validated['has_resi'] == '1') {
                $filePath = $request->file('resi_file')->getRealPath();

                $uploadResult = Cloudinary::uploadApi()->upload($filePath, [
                    'folder' => 'resi_files',
                    'resource_type' => 'raw',
                ]);

                $fileUrl = $uploadResult['secure_url'];

                $resi = Resi::create([
                    'resi_number' => $validated['resi_number'],
                    'file_path' => $fileUrl,
                    'file_name' => $validated['resi_number'] . '.' . $request->file('resi_file')->getClientOriginalExtension(),
                    'resi_source_id' => $validated['resi_source_id'],
                ]);
            }

            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'reseller_id' => auth()->id(),
                'resi_id' => $resi->id,
                'total_price' => $validated['total_price'],
                'shipping_address' => $shipping_address,
                'total_shipping' => $validated['total_shipping'],
                'note' => $validated['note'] ?? null,
            ]);

            $carts = Cart::with('variant.product')
                ->where('reseller_id', auth()->id())
                ->whereIn('id', $validated['cart_ids'])
                ->get();

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $cart->variant->id,
                    'product_name' => $cart->variant->product->name,
                    'quantity' => $cart->quantity,
                    'price_each' => $cart->variant->price,
                    'variant_name' => $cart->variant->name,
                    'shop_name' => $cart->variant->product->shop->name,
                ]);

                $cart->delete();
            }

            DB::commit();
            $reseller = auth()->guard('reseller')->user();

            NotificationHelper::notifyAdmins('Pesanan Baru', $reseller->name . ' melakukan pembelian Order #' . $order->order_code, route('orders.current'));

            NotificationHelper::notifyReseller($reseller, 'Pesanan Dibuat', 'Pesanan #' . $order->order_code . ' berhasil dibuat', route('order.history'));

            return redirect()->route('payment', $order->order_code);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->route('cart')->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('cart')
                ->withErrors(['resi_file' => 'Gagal mengunggah file resi silahkan coba lagi.'])
                ->withInput();
        }
    }
    public function payment($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();
        $total = $order->total_price;
        if (!$order || !$order->reseller_id == auth()->id()) {
            return redirect()->back()->with('error', 'order tidak ditemukan atau tidak berlaku untuk Anda.');
        }
        $whatsapp = Setting::where('key', 'whatsapp')->first()->value ?? '';
        $bankAccounts = json_decode(Setting::where('key', 'bank_accounts')->first()->value ?? '[]', true);
        $ewallets = json_decode(Setting::where('key', 'ewallets')->first()->value ?? '[]', true);

        // Susun menjadi format methods seperti sebelumnya
        $methods = [
            'Bank' => array_map(function ($bank) {
                return [
                    'id' => strtolower($bank['name']),
                    'name' => $bank['name'],
                    'type' => 'va',
                    'description' => 'Transfer ke rekening virtual ' . $bank['name'],
                    'va_number' => $bank['number'],
                    'steps' => ['Buka mobile banking / ATM', 'Masukkan nomor virtual account', 'Masukkan nominal pembayaran', 'Konfirmasi pembayaran'],
                ];
            }, $bankAccounts),

            'E-Wallet' => array_map(function ($wallet) {
                return [
                    'id' => strtolower($wallet['provider']),
                    'name' => $wallet['provider'],
                    'type' => 'ewallet',
                    'description' => 'Bayar pakai ' . $wallet['provider'],
                    'phone_number' => $wallet['number'],
                    'steps' => ['Buka aplikasi ' . $wallet['provider'], 'Pilih menu transfer / top-up', 'Masukkan nomor tujuan / scan QR', 'Masukkan nominal pembayaran', 'Konfirmasi pembayaran'],
                ];
            }, $ewallets),
        ];

        return view('store.profile.payment', compact('order', 'methods', 'total'));
    }
    public function paymentConfirm(Request $request)
    {
        $validated = $request->validate([
            'selected_method' => 'required|string|in:va,ewallet,qris',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048', // maksimal 2MB
        ]);
        $order = Order::where('order_code', $request->order_code)->first();
        if (!$order || !$order->reseller_id == auth()->id()) {
            return redirect()->back()->with('error', 'order tidak ditemukan atau tidak berlaku untuk Anda.');
        }
        $order->payment_method = $validated['selected_method'];

        $publicId = 'Pm/O-' . auth()->id() . '/' . $order->order_code;

        $payment = Cloudinary::uploadApi()->upload($request->file('bukti_pembayaran')->getRealPath(), [
            'public_id' => $publicId,
            'overwrite' => true,
            'resource_type' => 'image',
        ]);

        $order->update([
            'payment_proofs' => $payment['public_id'],
            'is_paid_at' => now(),
            'payment_method' => $validated['selected_method'],
        ]);
        NotificationHelper::notifyAdmins('Bukti Pembayaran Diterima', 'Bukti pembayaran untuk Order #' . $order->order_code . ' telah diterima.', route('orders.current'));
        return redirect()->route('order.history')->with('success', 'Bukti pembayaran sudah di kirimkan.');
    }

    public function orderCancel(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order || !$order->reseller_id == auth()->id()) {
            return redirect()->back()->with('error', 'Order tidak ditemukan atau tidak berlaku untuk Anda.');
        }

        if ($order->status !== 0) {
            return redirect()->back()->with('error', 'Hanya order yang belum dibayar yang bisa dibatalkan.');
        }

        $order->update(['status' => 4]);
        NotificationHelper::notifyReseller($order->reseller, 'Order Dibatalkan', 'Order #' . $order->order_code . ' telah dibatalkan.', route('order.history'));
        NotificationHelper::notifyAdmins('Order Dibatalkan', 'Order #' . $order->order_code . ' telah dibatalkan oleh reseller.', route('orders.current'));
        return redirect()->route('order.history')->with('success', 'Order berhasil dibatalkan.');
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
