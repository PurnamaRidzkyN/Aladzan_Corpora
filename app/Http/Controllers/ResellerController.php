<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\Rating;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResellerController extends Controller
{
    public function ShowHome()
    {
        $categories = Category::all();
        $products = Product::with('media', 'shop', 'rating', 'variants')->get();
        return view('home', compact('products', 'categories'));
    }
    public function showProduct($slug)
    {
        $product = Product::with(['media', 'shop', 'rating', 'reviews', 'variants' => fn($q) => $q->latest()->take(5), 'reviews.reseller'])
            ->where('slug', $slug)
            ->firstOrFail();

        $products = Product::with('media', 'shop', 'rating', 'variants')->get();

        $rating = DB::table('rating_summary_view')->where('product_id', $product->id)->first();

        return view('product_detail', compact('product', 'products', 'rating'));
    }

    public function search(Request $request)
    {
        $query = Product::with(['media', 'shop', 'rating', 'variants'])
            ->leftJoin('rating_summary_view as rsv', 'products.id', '=', 'rsv.product_id')
            ->select('products.*', 'rsv.rating as avg_rating', 'rsv.rating_count');

        if ($request->filled('q')) {
            $query->where('products.name', 'like', '%' . $request->q . '%');
        }

        switch ($request->sort) {
            case 'terlaris':
                $query->orderByDesc('sold');
                break;
            case 'rating':
                $query->orderByDesc('rsv.rating');
                break;
            case 'harga_tertinggi':
                $query->orderByDesc('price');
                break;
            case 'harga_terendah':
                $query->orderBy('price');
                break;
            default:
                $query->orderByDesc('products.created_at');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('products', compact('products', 'categories'));
    }
    public function kategori(Request $request, $slug)
    {
        $query = Product::with(['media', 'shop', 'rating', 'variants'])
            ->leftJoin('rating_summary_view as rsv', 'products.id', '=', 'rsv.product_id')
            ->select('products.*', 'rsv.rating as avg_rating', 'rsv.rating_count');

        $query->whereHas('categories', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });

        switch ($request->sort) {
            case 'terlaris':
                $query->orderByDesc('sold');
                break;
            case 'rating':
                $query->orderByDesc('rsv.rating');
                break;
            case 'harga_tertinggi':
                $query->orderByDesc('price');
                break;
            case 'harga_terendah':
                $query->orderBy('price');
                break;
            default:
                $query->orderByDesc('products.created_at');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('products', compact('products', 'categories', 'slug'));
    }
    public function shop(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();

        // Ambil semua kategori yang dimiliki oleh produk dari toko ini
        $categories = Category::whereHas('products', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->get();

        // Query produk yang berasal dari toko ini
        $query = Product::with(['media', 'rating', 'categories'])
            ->where('shop_id', $shop->id)
            ->leftJoin('rating_summary_view as rsv', 'products.id', '=', 'rsv.product_id')
            ->select('products.*', 'rsv.rating as avg_rating', 'rsv.rating_count');

        // Filter kategori jika ada
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter sorting
        switch ($request->sort) {
            case 'terlaris':
                $query->orderByDesc('sold');
                break;
            case 'rating':
                $query->orderByDesc('rsv.rating');
                break;
            case 'harga_tertinggi':
                $query->orderByDesc('price');
                break;
            case 'harga_terendah':
                $query->orderBy('price');
                break;
            default:
                $query->orderByDesc('products.created_at');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('shop', [
            'shop' => $shop,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
    public function favorite()
    {
        $reseller = auth()->user();
        $wishlists = $reseller->wishlists()->with('product', 'product.variants', 'product.rating', 'product.shop', 'product.media', 'product.categories')->paginate(12);
        return view('wishlists', compact('wishlists'));
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
    public function cart()
    {
        $userId = auth()->id();

        $cartItems = Cart::with(['variant.media', 'variant.product.shop']) // Sesuaikan relasi
            ->where('reseller_id', $userId)
            ->get()
            ->groupBy(fn($item) => $item->variant->product->shop->name ?? 'Toko Tidak Diketahui');
        return view('cart', compact('cartItems'));
    }

    public function handleCartOrBuy(Request $request)
    {
        if ($request->action === 'cart') {
            $validated = $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'quantity' => 'required|integer|min:1',
                'note' => 'nullable|string|max:255',
            ]);

            Cart::create([
                'reseller_id' => auth()->id(),
                'product_variant_id' => $validated['product_variant_id'],
                'quantity' => $validated['quantity'],
                'note' => $request->note,
            ]);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }

        // Tambahan untuk "buy_now" bisa kamu isi nanti
    }
}
