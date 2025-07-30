<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart()
    {
        $userId = auth()->id();

        $cartItems = Cart::with(['variant.media', 'variant.product.shop'])
            ->where('reseller_id', $userId)
            ->get()
            ->groupBy(fn($item) => $item->variant->product->shop->name ?? 'Toko Tidak Diketahui');
        return view('store.cart', compact('cartItems'));
    }

    public function handleCartOrBuy(Request $request)
    {
        if ($request->action === 'cart') {
            $validated = $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $existingCart = Cart::where('reseller_id', auth()->id())
                ->where('product_variant_id', $validated['product_variant_id'])
                ->first();

            if ($existingCart) {
                $existingCart->quantity += $validated['quantity'];
                $existingCart->save();
            } else {
                Cart::create([
                    'reseller_id' => auth()->id(),
                    'product_variant_id' => $validated['product_variant_id'],
                    'quantity' => $validated['quantity'],
                ]);
            }

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } elseif ($request->action === 'buy_now') {
            $validated = $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'quantity' => 'required|integer|min:1',
            ]);
            $cart = Cart::create([
                'reseller_id' => auth()->id(),
                'product_variant_id' => $validated['product_variant_id'],
                'quantity' => $validated['quantity'],
            ]);
           $request = new Request([
    'items_json' => json_encode([
        [
            'id' => $cart->id,
            'qty' => $validated['quantity'],
        ]
    ]),
]);

            return app(PaymentController::class)->chooseAddress($request); //
        }
    }
    public function cartDestroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return back()->with('success', 'Item berhasil dihapus dari keranjang!');
    }
}
