<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use Carbon\Carbon;

class DiscountController extends Controller
{
    public function check($code)
    {

        $discount = Discount::where('code', $code)
            ->where(function($q){
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            })
            ->first();

        if (!$discount) {
            return response()->json(['valid' => false, 'message' => 'Kode tidak valid']);
        }

        return response()->json([
            'valid' => true,
            'is_percent' => $discount->is_percent,
            'amount' => $discount->amount,
            'message' => 'Kode diskon diterapkan!'
        ]);
    }

    public function index(){
        $discount = Discount::all();
        return view('admin.discount.index', compact('discount'));
    }
    public function store(Request $request){
        $request->validate([
            'code' => 'required|string|max:100',
            'amount' => 'required|integer|min:1',
            'type' => 'required|integer',
            'valid_until' => 'required|date',
        ]);

        $discount = Discount::create([
            'code' => $request->code,
            'amount' => $request->amount,
            'is_percent' => $request->type == 1,
            'valid_until' => $request->valid_until,
        ]);

        return redirect()->route('discount.index')->with('success', 'Diskon berhasil ditambahkan.');
    }
    public function destroy($id){
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return redirect()->route('discount.index')->with('success', 'Diskon berhasil dihapus.');
    }
}
