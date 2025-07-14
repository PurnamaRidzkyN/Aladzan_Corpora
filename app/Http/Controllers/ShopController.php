<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = Shop::all();
        return view('admin.management_products.shop.index', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $shop = Shop::create($request->all());
        $folderName = 'S' . $shop->id;
        Storage::disk('google')->makeDirectory($folderName);
        return redirect()->route('shops.index')->with('success', 'Toko berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $shop = Shop::findOrFail($id);
        $shop->update($request->all());

        return redirect()->back()->with('success', 'Toko berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = Shop::findOrFail($id);
        Storage::disk('google')->deleteDirectory('S'. $shop->id);
        $shop->delete();

        return redirect()->route('shops.index')->with('success', 'Toko berhasil dihapus.');
    }
}
