<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            'zipcode' => 'required|string|max:255',
        ]);
        $address = Address::where('zipcode', $request->zipcode)->first();
        $shop = Shop::where('zipcode', $request->zipcode)->first();
        if ($address) {
            Shop::create([
                'name' => $request->name,
                'description' => $request->description,
                'zipcode' => $request->zipcode,
                'sub_district_id' => $address->sub_district_id,
                'city' => $address->city,
            ]);
            return redirect()->route('shops.index')->with('success', 'Toko berhasil ditambahkan.');
        } elseif ($shop) {
            Shop::create([
                'name' => $request->name,
                'description' => $request->description,
                'zipcode' => $request->zipcode,
                'sub_district_id' => $shop->sub_district_id,
                'city' => $shop->city,
            ]);
            return redirect()->route('shops.index')->with('success', 'Toko berhasil ditambahkan.');
        } else {
            $response = Http::withHeaders([
                'key' => config('services.rajaongkir.key'),
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $request->zipcode,
                'limit' => 1,
                'offset' => 0,
            ]);

            if (!$response->successful()) {
                return back()->withErrors(['address' => 'Gagal menghubungi layanan wilayah. Silakan coba kembali nanti.']);
            }

            $data = $response->json();

            if (empty($data['data'])) {
                return back()->withErrors(['address' => 'Kode pos tidak ditemukan. Mohon pastikan kode pos yang Anda masukkan benar.']);
            }
            Shop::create([
                'name' => $request->name,
                'description' => $request->description,
                'zipcode' => $request->zipcode,
                'sub_district_id' => $data['data'][0]['id'],
                'city' => $data['data'][0]['city_name'],
            ]);
        }
        return redirect()->route('shops.index')->with('success', 'Toko berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'zipcode' => 'nullable|string|max:255',
        ]);
        $shop = Shop::findOrFail($id);
        if ($request->zipcode != $shop->zipcode) {
            $address = Address::where('zipcode', $request->zipcode)->first();
            $sameShop = Shop::where('zipcode', $request->zipcode)->first();
            if ($address) {
                $shop->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'zipcode' => $request->zipcode,
                    'sub_district_id' => $sameShop->sub_district_id,
                    'city' => $sameShop->city,
                ]);
            } elseif ($sameShop) {
                $shop->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'zipcode' => $request->zipcode,
                    'sub_district_id' => $address->sub_district_id,
                    'city' => $address->city,
                ]);
            } else {
                $response = Http::withHeaders([
                    'key' => config('services.rajaongkir.key'),
                ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                    'search' => $request->zipcode,
                    'limit' => 1,
                    'offset' => 0,
                ]);

                if (!$response->successful()) {
                    return back()->withErrors(['address' => 'Gagal menghubungi layanan wilayah. Silakan coba kembali nanti.']);
                }

                $data = $response->json();

                if (empty($data['data'])) {
                    return back()->withErrors(['address' => 'Kode pos tidak ditemukan. Mohon pastikan kode pos yang Anda masukkan benar.']);
                }
                $shop->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'zipcode' => $request->zipcode,
                    'sub_district_id' => $data['data'][0]['id'],
                    'city' => $data['data'][0]['city_name'],
                ]);
            }
        } else {
            $shop->update(['name' => $request->name, 'description' => $request->description]);
        }
        return redirect()->back()->with('success', 'Toko berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();

        return redirect()->route('shops.index')->with('success', 'Toko berhasil dihapus.');
    }
}
