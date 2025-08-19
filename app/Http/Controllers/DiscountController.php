<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Helpers\AdminActivityHelper;

class DiscountController extends Controller
{
    public function check($code)
    {
        $discount = Discount::where('code', $code)
            ->where(function ($q) {
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
            'message' => 'Kode diskon diterapkan!',
        ]);
    }

    public function index()
    {
        $discount = Discount::all();
        return view('admin.discount.index', compact('discount'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'code' => 'required|string|max:100',
                'amount' => 'required|integer|min:1',
                'type' => 'required|integer',
                'valid_until' => 'required|date',
            ],
            [
                'code.required' => 'Kode wajib diisi.',
                'code.string' => 'Kode harus berupa teks.',
                'code.max' => 'Kode maksimal 100 karakter.',
                'amount.required' => 'Jumlah wajib diisi.',
                'amount.integer' => 'Jumlah harus berupa angka.',
                'amount.min' => 'Jumlah minimal 1.',
                'type.required' => 'Tolong pilih salah satu tipe.',
                'type.integer' => 'Tipe tidak valid.',
                'valid_until.required' => 'Tanggal berlaku wajib diisi.',
                'valid_until.date' => 'Tanggal berlaku tidak valid.',
            ],
        );

        $discount = Discount::create([
            'code' => $request->code,
            'amount' => $request->amount,
            'is_percent' => $request->type == 1,
            'valid_until' => $request->valid_until,
        ]);
        // Log aktivitas admin
        AdminActivityHelper::log('CREATE', 'discounts', $discount->id, 'Menambahkan diskon: ' . $request->code);

        return redirect()->route('discount.index')->with('success', 'Diskon berhasil ditambahkan.');
    }
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        AdminActivityHelper::log('DELETE', 'discounts', $discount->id, 'Menghapus diskon: ' . $discount->code);
        $discount->delete();
        return redirect()->route('discount.index')->with('success', 'Diskon berhasil dihapus.');
    }
}
