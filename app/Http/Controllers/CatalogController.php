<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
     public function ShowHome()
    {
        $categories = Category::all();
        $products = Product::with('media', 'shop', 'rating', 'variants')->get();
        return view('store.catalog.home', compact('products', 'categories'));
    }
    public function showProduct($slug)
    {
        $product = Product::with(['media', 'shop', 'rating', 'review', 'variants' => fn($q) => $q->latest()->take(5), 'review.reseller'])
            ->where('slug', $slug)
            ->firstOrFail();

        $products = Product::with('media', 'shop', 'rating', 'variants')->get();

        $rating = DB::table('rating_summary_view')->where('product_id', $product->id)->first();

        return view('store.catalog.product_detail', compact('product', 'products', 'rating'));
    }

    public function search(Request $request)
    {
        $query = Product::with(['media', 'shop', 'rating', 'variants'])
            ->leftJoin('rating_summary_view as rsv', 'products.id', '=', 'rsv.product_id')
            ->select('products.*', 'rsv.rating as avg_rating', 'rsv.rating_count')
            ->selectSub(function ($q) {
                $q->from('product_variants')->selectRaw('MIN(price)')->whereColumn('product_variants.product_id', 'products.id');
            }, 'min_price')
            ->selectSub(function ($q) {
                $q->from('product_variants')->selectRaw('MAX(price)')->whereColumn('product_variants.product_id', 'products.id');
            }, 'max_price');
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
                $query->orderByDesc('max_price');
                break;
            case 'harga_terendah':
                $query->orderBy('min_price');
                break;
            default:
                $query->orderByDesc('products.created_at');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('store.catalog.products', compact('products', 'categories'));
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

        return view('store.catalog.products', compact('products', 'categories', 'slug'));
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

        return view('store.catalog.shop', [
            'shop' => $shop,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
