<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Models\Order;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['review', 'shop', 'rating', 'categories'])
            ->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        // Ambil semua ulasan untuk produk ini, hanya yang ada komentarnya
        $ratings = Rating::with(['reseller', 'product', 'admin'])
            ->where('product_id', $product->id)
            ->whereNotNull('comment')
            ->latest()
            ->get();

        $averageRating = round($ratings->avg('rating'), 1);
        $totalReviews = $ratings->count();
        return view('admin.management_products.review.index', compact('ratings', 'averageRating', 'totalReviews', 'product'));
    }
    public function review(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|numeric|between:1,5',
            'comment' => 'nullable|string',
            'order_id' => 'required|exists:orders,id',
        ]);
        $orderCode = Order::findOrFail($validated['order_id'])->order_code;
        $reseller = auth()->user();
        Rating::create([
            'product_id' => $validated['product_id'],
            'reseller_id' => $reseller->id,
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'comment_at' => now(),
        ]);
        NotificationHelper::notifyAdmins('Ulasan Baru', 'Pesanan #' . $orderCode . ' telah diulas oleh reseller' . $reseller->name . '.', route('reviews.show', ['slug' => Product::find($validated['product_id'])->slug]));
        return redirect()->back()->with('success', 'Terima kasih telah memberikan rating.');
    }

    public function reviewReply(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);
        $rating->reply = $request->input('reply');
        $rating->reply_at = now();
        $rating->admin_id = auth('admin')->user()->id;
        $rating->save();

        NotificationHelper::notifyReseller($rating->reseller, 'Balasan Ulasan', 'Pesanan #' . $rating->order->order_code . ' telah mendapatkan balasan dari admin.', route('order.detail', ['order_code' => $rating->order->order_code]));
        return redirect()->back()->with('success', 'Berhasil menambahkan balasan.');
    }
}
