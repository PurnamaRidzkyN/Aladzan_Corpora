<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = auth()->user()->addresses()->get();
        $chooseeAddress = false;
        return view('store.profile.address', compact('addresses', 'chooseeAddress'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reseller_id' => 'required|exists:resellers,id',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'hamlet' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'zipcode' => 'required|string|max:255',
            'address_detail' => 'required|string',
        ]);
        $address = Address::where('zipcode', $request->zipcode)->first();
        if ($address) {
            Address::create([
                'reseller_id' => $request->reseller_id,
                'recipient_name' => $request->recipient_name,
                'phone_number' => $request->phone_number,
                'neighborhood' => $request->neighborhood,
                'hamlet' => $request->hamlet,
                'village' => $request->village,
                'zipcode' => $request->zipcode,
                'address_detail' => $request->address_detail,
                'province' => $address->province,
                'city' => $address->city,
                'district' => $address->district,
                'sub_district' => $address->sub_district,
                'sub_district_id' => $address->sub_district_id,
            ]);
            return redirect()->back()->with('success', 'Alamat berhasil ditambahkan.');
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
            Address::create([
                'reseller_id' => $request->reseller_id,
                'recipient_name' => $request->recipient_name,
                'phone_number' => $request->phone_number,
                'neighborhood' => $request->neighborhood,
                'hamlet' => $request->hamlet,
                'village' => $request->village,
                'zipcode' => $request->zipcode,
                'address_detail' => $request->address_detail,
                'province' => $data['data'][0]['province_name'],
                'city' => $data['data'][0]['city_name'],
                'district' => $data['data'][0]['district_name'],
                'sub_district' => $data['data'][0]['subdistrict_name'],
                'sub_district_id' => $data['data'][0]['id'],
            ]);
            if ($request->has('items_json')) {
                $request = new Request([
                    'items_json' => $request->items_json,
                ]);
                return app(PaymentController::class)->chooseAddress($request);
            }
            return redirect()->back()->with('success', 'Alamat berhasil ditambahkan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'reseller_id' => 'required|exists:resellers,id',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'hamlet' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'zipcode' => 'required|string|max:255',
            'address_detail' => 'required|string',
        ]);
        $address = Address::findOrFail($id);
        if ($request->zipcode != $address->zipcode) {
            $sameAddress = Address::where('zipcode', $request->zipcode)->first();
            if ($sameAddress) {
                $address->update([
                    'reseller_id' => $request->reseller_id,
                    'recipient_name' => $request->recipient_name,
                    'phone_number' => $request->phone_number,
                    'neighborhood' => $request->neighborhood,
                    'hamlet' => $request->hamlet,
                    'village' => $request->village,
                    'zipcode' => $request->zipcode,
                    'address_detail' => $request->address_detail,
                    'province' => $sameAddress->province,
                    'city' => $sameAddress->city,
                    'district' => $sameAddress->district,
                    'sub_district' => $sameAddress->sub_district,
                    'sub_district_id' => $sameAddress->sub_district_id,
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
                $address->update([
                    'recipient_name' => $request->recipient_name,
                    'phone_number' => $request->phone_number,
                    'neighborhood' => $request->neighborhood,
                    'hamlet' => $request->hamlet,
                    'village' => $request->village,
                    'zipcode' => $request->zipcode,
                    'address_detail' => $request->address_detail,
                    'province' => $data['data'][0]['province_name'],
                    'city' => $data['data'][0]['city_name'],
                    'district' => $data['data'][0]['district_name'],
                    'sub_district' => $data['data'][0]['subdistrict_name'],
                    'sub_district_id' => $data['data'][0]['id'],
                ]);
            }
        } else {
            $address->update(['recipient_name' => $request->recipient_name, 'phone_number' => $request->phone_number, 'neighborhood' => $request->neighborhood, 'hamlet' => $request->hamlet, 'village' => $request->village, 'address_detail' => $request->address_detail]);
        }
        if ($request->has('items_json')) {
            $request = new Request([
                'items_json' => $request->items_json,
            ]);
            return app(PaymentController::class)->chooseAddress($request);
        }

        return redirect()->back()->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        if ($request->has('items_json')) {
            $request = new Request([
                'items_json' => $request->items_json,
            ]);
            return app(PaymentController::class)->chooseAddress($request);
        }
        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}
