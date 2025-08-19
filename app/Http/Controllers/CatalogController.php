<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\WebRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function ShowHome()
    {
        $categories = Category::all();
        $products = Product::with(['media', 'shop', 'rating', 'variants'])
            ->withSum(
                [
                    'orderItems as sold' => function ($q) {
                        $q->whereHas('order', fn($q) => $q->where('status', 3));
                    },
                ],
                'quantity',
            )
            ->orderByDesc('sold') // urutkan berdasarkan total penjualan
            ->take(20) // ambil 10 produk teratas
            ->get();

        $order = null;
        if (auth()->check()) {
            $resellerId = auth()->id();
            $hasRated = WebRating::where('reseller_id', $resellerId)->exists();
            if (!$hasRated) {
                $order = Order::where('reseller_id', $resellerId)
                    ->where('created_at', '>=', now()->subMinute())
                    ->first();
            }
        }
        return view('store.catalog.home', compact('order', 'products', 'categories'));
    }
    public function showProduct($slug)
    {
        $product = Product::with(['media', 'shop', 'rating', 'review.reseller', 'review.admin', 'variants'])
            ->withSum(
                [
                    'orderItems as sold' => fn($q) => $q->whereHas('order', fn($q) => $q->where('status', 3)),
                ],
                'quantity',
            )
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with(['media', 'shop', 'rating', 'variants'])
            ->withSum(
                [
                    'orderItems as sold' => fn($q) => $q->whereHas('order', fn($q) => $q->where('status', 3)),
                ],
                'quantity',
            )
            ->where('shop_id', $product->shop_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(7)
            ->get();
        $waSetting = Setting::where('key', 'whatsapp')->first();
        $wa = $waSetting ? $waSetting->value : null;

        $rating = DB::table('rating_summary_view')->where('product_id', $product->id)->first();
        $latestReviews = $product->review()->with('reseller')->latest()->take(5)->get();
        return view('store.catalog.product_detail', compact('product', 'wa', 'relatedProducts', 'rating', 'latestReviews'));
    }

    public function search(Request $request)
    {
        $query = Product::with(['media', 'shop', 'rating', 'variants'])
            ->withSum(
                [
                    'orderItems as sold' => function ($q) {
                        $q->whereHas('order', fn($q) => $q->where('status', 3));
                    },
                ],
                'quantity',
            )
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
        $query->selectSub(function ($q) {
            $q->from('order_items')->join('orders', 'orders.id', '=', 'order_items.order_id')->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')->where('orders.status', 3)->whereColumn('product_variants.product_id', 'products.id')->selectRaw('COALESCE(SUM(order_items.quantity), 0)');
        }, 'sold');
        $query->selectSub(function ($q) {
            $q->from('product_variants')->selectRaw('MIN(price)')->whereColumn('product_variants.product_id', 'products.id');
        }, 'min_price');

        $query->selectSub(function ($q) {
            $q->from('product_variants')->selectRaw('MAX(price)')->whereColumn('product_variants.product_id', 'products.id');
        }, 'max_price');

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
            ->withSum(
                [
                    'orderItems as sold' => function ($q) {
                        $q->whereHas('order', fn($q) => $q->where('status', 3));
                    },
                ],
                'quantity',
            )
            ->leftJoin('rating_summary_view as rsv', 'products.id', '=', 'rsv.product_id')
            ->select('products.*', 'rsv.rating as avg_rating', 'rsv.rating_count');

        $query->whereHas('categories', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
        $query->selectSub(function ($q) {
            $q->from('order_items')->join('orders', 'orders.id', '=', 'order_items.order_id')->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')->where('orders.status', 3)->whereColumn('product_variants.product_id', 'products.id')->selectRaw('COALESCE(SUM(order_items.quantity), 0)');
        }, 'sold');

        $query->selectSub(function ($q) {
            $q->from('product_variants')->selectRaw('MIN(price)')->whereColumn('product_variants.product_id', 'products.id');
        }, 'min_price');

        $query->selectSub(function ($q) {
            $q->from('product_variants')->selectRaw('MAX(price)')->whereColumn('product_variants.product_id', 'products.id');
        }, 'max_price');
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
            ->withSum(
                [
                    'orderItems as sold' => function ($q) {
                        $q->whereHas('order', fn($q) => $q->where('status', 3));
                    },
                ],
                'quantity',
            )
            ->where('shop_id', $shop->id)
            ->leftJoin('rating_summary_view as rsv', 'products.id', '=', 'rsv.product_id')
            ->select('products.*', 'rsv.rating as avg_rating', 'rsv.rating_count');

        // Filter kategori jika ada
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        $query->selectSub(function ($q) {
            $q->from('order_items')->join('orders', 'orders.id', '=', 'order_items.order_id')->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')->where('orders.status', 3)->whereColumn('product_variants.product_id', 'products.id')->selectRaw('COALESCE(SUM(order_items.quantity), 0)');
        }, 'sold');
        $query->selectSub(function ($q) {
            $q->from('product_variants')->selectRaw('MIN(price)')->whereColumn('product_variants.product_id', 'products.id');
        }, 'min_price');

        $query->selectSub(function ($q) {
            $q->from('product_variants')->selectRaw('MAX(price)')->whereColumn('product_variants.product_id', 'products.id');
        }, 'max_price');
        // Filter sorting
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

        return view('store.catalog.shop', [
            'shop' => $shop,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
