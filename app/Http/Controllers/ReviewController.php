<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['review', 'shop','rating','categories'])
            ->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        // Ambil semua ulasan untuk produk ini, hanya yang ada komentarnya
        $ratings = Rating::with(['reseller', 'product'])
            ->where('product_id', $product->id)
            ->whereNotNull('comment')
            ->latest()
            ->get();

        $averageRating = round($ratings->avg('rating'), 1);
        $totalReviews = $ratings->count();
        return view('admin.management_products.review.index', compact('ratings', 'averageRating', 'totalReviews','product'));
    }
    public function review(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|numeric|between:1,5',
            'comment' => 'nullable|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        Rating::create([
            'product_id' => $validated['product_id'],
            'reseller_id' => auth()->id(),
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'comment_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Terima kasih telah memberikan rating.');
    }

    public function reviewReply(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);
        $rating->reply = $request->input('reply');
        $rating->reply_at = now();
        $rating->save();
        return redirect()->back()->with('success', 'Berhasil menambahkan balasan.');
    }
}
