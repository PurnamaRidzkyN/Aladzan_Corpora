<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\WebRating;
use Illuminate\Http\Request;

class WebRatingController extends Controller
{
    public function index()
    {
        return view('static.feedback');
    }
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        WebRating::create([
            'reseller_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('home')->with('success', 'Terima kasih atas rating Anda!');
    }
    public function feedback(Request $request)
    {
        $query = WebRating::query();

        // Filter berdasarkan rating
        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }

        $feedbacks = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reseller.feedback', compact('feedbacks'));
    }
}
