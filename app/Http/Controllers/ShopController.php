<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Address;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\AdminActivityHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::guard('admin')->user()->is_super_admin) {
            // Superadmin: bisa lihat semua shop termasuk yang soft delete
            $shops = Shop::withTrashed()->get();
        } else {
            // Admin biasa: hanya shop yang masih aktif
            $shops = Shop::all();
        }

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
            'video_path' => 'nullable|string|max:255',
        ]);

        // Default img_path
        $imgPath = null;

        // Upload ke Cloudinary jika ada gambar
        if ($request->hasFile('img_path')) {
            $uploadedFile = $request->file('img_path');
            $uploadResult = Cloudinary::uploadApi()->upload($uploadedFile->getRealPath(), [
                'folder' => 'shop/' . Str::slug($request->name),
                'overwrite' => true,
                'resource_type' => 'image',
            ]);
            $imgPath = $uploadResult['public_id'];
        }
        $videoPath = $request->input('video_path');

        if ($videoPath) {
            // Cek apakah YouTube link
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w\-]+)/', $videoPath, $matches)) {
                // Ambil ID video-nya
                $videoId = $matches[1];
            } else {
                // Bukan link YouTube valid, tolak input
                return back()
                    ->withErrors(['video_path' => 'Masukkan link YouTube yang valid.'])
                    ->withInput();
            }
        } else {
            $videoId = null;
        }

        $address = Address::where('zipcode', $request->zipcode)->first();
        $shop = Shop::where('zipcode', $request->zipcode)->first();
        if ($address) {
            Shop::create([
                'name' => $request->name,
                'description' => $request->description,
                'zipcode' => $request->zipcode,
                'sub_district_id' => $address->sub_district_id,
                'city' => $address->city,
                'img_path' => $imgPath,
                'video_path' => $videoId,
            ]);
            return redirect()->route('shops.index')->with('success', 'Toko berhasil ditambahkan.');
        } elseif ($shop) {
            Shop::create([
                'name' => $request->name,
                'description' => $request->description,
                'zipcode' => $request->zipcode,
                'sub_district_id' => $shop->sub_district_id,
                'city' => $shop->city,
                'img_path' => $imgPath,
                'video_path' => $videoId,
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
            $shop = Shop::create([
                'name' => $request->name,
                'description' => $request->description,
                'zipcode' => $request->zipcode,
                'sub_district_id' => $data['data'][0]['id'],
                'city' => $data['data'][0]['city_name'],
                'img_path' => $imgPath,
                'video_path' => $videoId,
            ]);
        }
        // Log aktivitas admin
        AdminActivityHelper::log('CREATE', 'shops', $shop->id, 'Menambahkan toko: ' . $request->name);
        return redirect()->route('shops.index')->with('success', 'Toko berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'zipcode' => 'nullable|string|max:255',
            'video_path' => 'nullable|string|max:255',
            'img_path' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        $shop = Shop::findOrFail($id);
        $originalShop = $shop->getOriginal();
        $logDetails = [];

        // --- Upload image ---
        if ($request->hasFile('img_path')) {
            $uploadedFile = $request->file('img_path');
            $uploadResult = Cloudinary::uploadApi()->upload($uploadedFile->getRealPath(), [
                'folder' => 'shop/' . Str::slug($request->name),
                'overwrite' => true,
                'resource_type' => 'image',
            ]);
            $shop->img_path = $uploadResult['public_id'];
            $logDetails[] = 'Ganti gambar toko';
        }

        // --- Validasi dan update video path ---
        if (!empty($validated['video_path']) && $validated['video_path'] !== $shop->video_path) {
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w\-]+)/', $validated['video_path'], $matches)) {
                $videoId = $matches[1];
                $validated['video_path'] = $videoId;
                $logDetails[] = "Video path: '{$originalShop['video_path']}' → '{$videoId}'";
            } else {
                return back()
                    ->withErrors(['video_path' => 'Masukkan link YouTube yang valid.'])
                    ->withInput();
            }
        }

        // --- Update alamat jika zipcode berubah ---
        if ($validated['zipcode'] !== $shop->zipcode) {
            $address = Address::where('zipcode', $validated['zipcode'])->first();
            $sameShop = Shop::where('zipcode', $validated['zipcode'])->first();

            if ($address) {
                $shop->sub_district_id = $address->sub_district_id;
                $shop->city = $address->city;
            } elseif ($sameShop) {
                $shop->sub_district_id = $sameShop->sub_district_id;
                $shop->city = $sameShop->city;
            } else {
                $response = Http::withHeaders([
                    'key' => config('services.rajaongkir.key'),
                ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                    'search' => $validated['zipcode'],
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

                $shop->sub_district_id = $data['data'][0]['id'];
                $shop->city = $data['data'][0]['city_name'];
            }

            $logDetails[] = "Zipcode: '{$originalShop['zipcode']}' → '{$validated['zipcode']}'";
        }

        // --- Update nama & deskripsi ---
        if ($validated['name'] !== $shop->name) {
            $logDetails[] = "Nama: '{$originalShop['name']}' → '{$validated['name']}'";
        }
        if ($validated['description'] !== $shop->description) {
            $logDetails[] = 'Deskripsi diubah';
        }
        $shop->fill($validated);
        $shop->save();

        // --- Log aktivitas ---
        $description = 'Mengubah toko: ' . $shop->name;
        if (!empty($logDetails)) {
            $description .= ' | Perubahan: ' . implode(', ', $logDetails);
        }
        AdminActivityHelper::log('UPDATE', 'shops', $shop->id, $description);

        return redirect()->back()->with('success', 'Toko berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();
        // Log aktivitas admin
        AdminActivityHelper::log('DELETE', 'shops', $shop->id, 'Menghapus toko: ' . $shop->name);
        return redirect()->route('shops.index')->with('success', 'Toko berhasil dihapus.');
    }

    public function restore($id)
    {
        if (Auth::guard('admin')->user()->is_super_admin) {
            $shop = Shop::onlyTrashed()->findOrFail($id);
            $shop->restore();
        } else {
            return redirect()->route('shops.index')->with('error', 'Maaf, Anda tidak memiliki izin. Hanya Super Admin yang bisa memulihakan toko.');
        }

        return redirect()->route('shops.index')->with('success', 'Shop berhasil direstore.');
    }

    public function forceDelete($id)
    {
        if (Auth::guard('admin')->user()->is_super_admin) {
            $shop = Shop::onlyTrashed()->findOrFail($id);
            // Hapus gambar dari Cloudinary jika ada
            if ($shop->img_path) {
                try {
                    Cloudinary::uploadApi()->destroy($shop->img_path);
                } catch (\Exception $e) {
                    \Log::warning("Gagal menghapus gambar toko: {$shop->img_path} — {$e->getMessage()}");
                }
            }
            $folder = 'P/S' . $shop->id;

// Ambil semua asset di folder
$assets = Cloudinary::adminApi()->assets([
    'type'   => 'upload',
    'prefix' => $folder,
]);

foreach ($assets['resources'] as $asset) {
    Cloudinary::uploadApi()->destroy($asset['public_id']);
}

// Setelah kosong, hapus folder
Cloudinary::adminApi()->deleteFolder($folder);
            $shop->forceDelete();
        } else {
            return redirect()->route('shops.index')->with('error', 'Maaf, Anda tidak memiliki izin. Hanya Super Admin yang bisa menghapus permanent toko.');
        }

        return redirect()->route('shops.index')->with('success', 'Shop berhasil dihapus permanen.');
    }
}
