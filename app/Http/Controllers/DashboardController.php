<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use App\Models\Reseller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Jumlah reseller
        $jumlahReseller = Reseller::count();
        $salesPerMonth = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total_sales')->where('status', 3)->groupBy('month')->get()->mapWithKeys(fn ($item) => [$item->month => $item->total_sales]);

        $bulan = [];
        $totalPenjualan = [];
        foreach (range(1, 12) as $m) {
            $bulan[] = date('M', mktime(0, 0, 0, $m, 1));
            $totalPenjualan[] = $salesPerMonth[$m] ?? 0;
        }

        // Top 3 reseller
        $topResellers = Order::select('reseller_id', DB::raw('COUNT(*) as total_orders'))->where('status', 3)->groupBy('reseller_id')->orderByDesc('total_orders')->take(3)->with('reseller:id,name')->get();

        $resellerNames = $topResellers->pluck('reseller.name');
        $resellerOrders = $topResellers->pluck('total_orders');

        // Toko terbaik & komposisi produk
        // 1. Cari toko dengan rating tertinggi
        $bestShop = DB::table('rating_summary_view')->join('products', 'rating_summary_view.product_id', '=', 'products.id')->join('shops', 'products.shop_id', '=', 'shops.id')->select('shops.id', 'shops.name', DB::raw('AVG(rating_summary_view.rating) as avg_rating'))->groupBy('shops.id', 'shops.name')->orderByDesc('avg_rating')->first();

        // 2. Cari produk top di toko tersebut
        $productComposition = collect();
        if ($bestShop) {
            $productComposition = DB::table('rating_summary_view')->join('products', 'rating_summary_view.product_id', '=', 'products.id')->where('products.shop_id', $bestShop->id)->select('products.name', 'rating_summary_view.rating')->orderByDesc('rating_summary_view.rating')->take(3)->pluck('rating', 'products.name');
        }
        // Rating tertinggi per toko
        $bestRatings = DB::table('ratings')->join('product_variants', 'ratings.product_id', '=', 'product_variants.id')->join('products', 'product_variants.product_id', '=', 'products.id')->join('shops', 'products.shop_id', '=', 'shops.id')->select('shops.name as shop_name', DB::raw('ROUND(AVG(ratings.rating), 2) as avg_rating'))->groupBy('shops.id', 'shops.name')->orderByDesc('avg_rating')->take(3)->get();

        // Rating terburuk per toko
        $worstRatings = DB::table('ratings')->join('product_variants', 'ratings.product_id', '=', 'product_variants.id')->join('products', 'product_variants.product_id', '=', 'products.id')->join('shops', 'products.shop_id', '=', 'shops.id')->select('shops.name as shop_name', DB::raw('ROUND(AVG(ratings.rating), 2) as avg_rating'))->groupBy('shops.id', 'shops.name')->orderBy('avg_rating')->take(3)->get();
        $bestRatingNames = $bestRatings->pluck('shop_name');
        $bestRatingValues = $bestRatings->pluck('avg_rating');

        $worstRatingNames = $worstRatings->pluck('shop_name');
        $worstRatingValues = $worstRatings->pluck('avg_rating');
        $topProducts = DB::table('order_items')->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')->join('products', 'product_variants.product_id', '=', 'products.id')->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))->groupBy('products.name')->orderByDesc('total_sold')->take(3)->get();

        $bestProducts = DB::table('ratings')->join('products', 'ratings.product_id', '=', 'products.id')->select('products.name', DB::raw('AVG(ratings.rating) as avg_rating'))->groupBy('products.name')->orderByDesc('avg_rating')->take(3)->get();

        $worstProducts = DB::table('ratings')->join('products', 'ratings.product_id', '=', 'products.id')->select('products.name', DB::raw('AVG(ratings.rating) as avg_rating'))->groupBy('products.name')->orderBy('avg_rating')->take(3)->get();

        return view('dashboard_admin', compact('jumlahReseller', 'bulan', 'totalPenjualan', 'resellerNames', 'resellerOrders', 'productComposition', 'bestShop', 'bestRatingNames', 'bestRatingValues', 'worstRatingNames', 'worstRatingValues', 'topProducts', 'bestProducts', 'worstProducts'));
    }
    public function resellerDashboard()
    {
        $totalPembelian = Order::where('reseller_id', auth()->id())->where('status', 3)->count();
        $totalBelanja = Order::where('reseller_id', auth()->id())->where('status', 3)->sum('total_price');

        $salesPerMonth = Order::where('reseller_id', auth()->id())
        ->where('status', 3)
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total_sales')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_sales', 'month');

        $bulan = [];
        $totalPenjualan = [];
        foreach (range(1, 12) as $m) {
            $bulan[] = date('M', mktime(0, 0, 0, $m, 1));
            $totalPenjualan[] = $salesPerMonth[$m] ?? 0;
        }

        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('ratings', 'products.id', '=', 'ratings.product_id')
            ->where('orders.reseller_id', auth()->id())
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_beli'), DB::raw('ROUND(AVG(ratings.rating), 2) as avg_rating'))
            ->groupBy('products.name')
            ->orderByDesc('total_beli')
            ->take(5)
            ->get();
        $avgRatingBought = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('ratings', 'products.id', '=', 'ratings.product_id')
            ->where('orders.reseller_id', auth()->id())
            ->avg('ratings.rating');
        $jumlahToko = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->where('orders.reseller_id', auth()->id())
            ->distinct('shops.id')
            ->count('shops.id');
        $jumlahProdukUnik = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.reseller_id', auth()->id())
            ->distinct('order_items.product_variant_id')
            ->count('order_items.product_variant_id');

        return view('dashboard_reseller', compact(
            'totalPembelian',
            'totalBelanja',
            'bulan',
            'totalPenjualan',
            'topProducts',
            'avgRatingBought',
            'jumlahToko',
            'jumlahProdukUnik'
        ));
    }
}
