<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
     public function favorite()
    {
        $reseller = auth()->user();
        $wishlists = $reseller->wishlists()->with('product', 'product.variants', 'product.rating', 'product.shop', 'product.media', 'product.categories')->paginate(12);
        return view('store.wishlists', compact('wishlists'));
    }
    public function favoriteStore(Request $request)
    {
        $reseller = auth()->user();

        // Cek apakah sudah ada
        $exists = Wishlist::where('reseller_id', $reseller->id)->where('product_id', $request->product_id)->exists();

        if (!$exists) {
            Wishlist::create([
                'reseller_id' => $reseller->id,
                'product_id' => $request->product_id,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke wishlist.');
    }

    public function favoriteDestroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();
        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
