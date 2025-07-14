<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_Permission;

class ProductController extends Controller
{
    public function index($id)
    {
        $shop = Shop::findOrFail($id);
        $products = Product::with('categories', 'media')->where('shop_id', $shop->id)->get();

        $categories = Category::all();
        return view('admin.management_products.product.index', compact('shop', 'products', 'categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'categories' => 'nullable|array',
            'description' => 'nullable|string',
            'shop_id' => 'required|exists:shops,id',
        ]);

        $product = Product::create($data);

        // 3. Simpan kategori jika ada (opsional tergantung relasi)
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        // 4. Simpan File
        $folderName = 'S' . $data['shop_id'] . '/P' . $product->id;
        Storage::disk('google')->makeDirectory($folderName);
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $filename = $folderName . '/' . uniqid() . '_' . $file->getClientOriginalName();

                // ⬇️ Simpan ke Google Drive
                $path = Storage::disk('google')->putFileAs('', $file, $filename);

                // Ambil ID file dari metadata
                $adapter = Storage::disk('google')->getAdapter();
                $metadata = $adapter->getMetadata($path);
                $fileId = $metadata['extraMetadata']['id'] ?? null;

                // ✅ Buat file Google Drive menjadi PUBLIC
                if ($fileId) {
                    $client = new Google_Client();
                    $client->setClientId(config('filesystems.disks.google.client_id'));
                    $client->setClientSecret(config('filesystems.disks.google.client_secret'));
                    $client->refreshToken(config('filesystems.disks.google.refresh_token'));

                    $service = new Google_Service_Drive($client);
                    $permission = new Google_Service_Drive_Permission([
                        'type' => 'anyone',
                        'role' => 'reader',
                    ]);
                    $service->permissions->create($fileId, $permission);
                }

                // Simpan ke database
                ProductMedia::create([
                    'product_id' => $product->id,
                    'file_path' => $fileId, // menyimpan fileId
                    'file_type' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Produk berhasil disimpan!');
    }
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'categories' => 'nullable|array',
            'description' => 'nullable|string',
            'shop_id' => 'required|exists:shops,id',
        ]);
        $product = Product::findOrFail($id);
        $product->update($data);

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        $folderName = 'S' . $data['shop_id'] . '/P' . $product->id;
        Storage::disk('google')->makeDirectory($folderName);
        if ($request->has('deleted_media')) {
            foreach ($request->deleted_media as $mediaId) {
                $media = $product->media()->where('id', $mediaId)->first();
                if ($media) {
                    // Hapus dari Google Drive
                    try {
                        $client = new \Google_Client();
                        $client->setClientId(config('filesystems.disks.google.client_id'));
                        $client->setClientSecret(config('filesystems.disks.google.client_secret'));
                        $client->refreshToken(config('filesystems.disks.google.refresh_token'));

                        $service = new \Google_Service_Drive($client);
                        $service->files->delete($media->file_path); // file_path = fileId
                    } catch (\Exception $e) {
                        // Log atau abaikan kalau file sudah dihapus manual
                        \Log::warning("Gagal menghapus file Google Drive ID: {$media->file_path} — {$e->getMessage()}");
                    }

                    // Hapus dari database
                    $media->delete();
                }
            }
        }

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $filename = $folderName . '/' . uniqid() . '_' . $file->getClientOriginalName();

                // ⬇️ Simpan ke Google Drive
                $path = Storage::disk('google')->putFileAs('', $file, $filename);

                // Ambil ID file dari metadata
                $adapter = Storage::disk('google')->getAdapter();
                $metadata = $adapter->getMetadata($path);
                $fileId = $metadata['extraMetadata']['id'] ?? null;

                // ✅ Buat file Google Drive menjadi PUBLIC
                if ($fileId) {
                    $client = new Google_Client();
                    $client->setClientId(config('filesystems.disks.google.client_id'));
                    $client->setClientSecret(config('filesystems.disks.google.client_secret'));
                    $client->refreshToken(config('filesystems.disks.google.refresh_token'));

                    $service = new Google_Service_Drive($client);
                    $permission = new Google_Service_Drive_Permission([
                        'type' => 'anyone',
                        'role' => 'reader',
                    ]);
                    $service->permissions->create($fileId, $permission);
                }

                // Simpan ke database
                ProductMedia::create([
                    'product_id' => $product->id,
                    'file_path' => $fileId, // menyimpan fileId
                    'file_type' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function show($id)
    {
        // Tampilkan detail produk (opsional)
    }

    public function destroy($id)
    {
        $product = Product::with('media')->findOrFail($id);

        // Hapus semua media dari Google Drive dan database
        foreach ($product->media as $media) {
            // 1. Hapus dari Google Drive
            try {
                $client = new \Google_Client();
                $client->setClientId(config('filesystems.disks.google.client_id'));
                $client->setClientSecret(config('filesystems.disks.google.client_secret'));
                $client->refreshToken(config('filesystems.disks.google.refresh_token'));

                $service = new \Google_Service_Drive($client);
                $service->files->delete($media->file_path);
            } catch (\Exception $e) {
                \Log::warning("Gagal menghapus file Google Drive ID: {$media->file_path} — {$e->getMessage()}");
            }

            // 2. Hapus dari database
            $media->delete();
        }
        $folderPath = 'S' . $product->shop_id . '/P' . $product->id;
        Storage::disk('google')->deleteDirectory($folderPath);
        // 3. Hapus relasi kategori (opsional tapi rapi)
        $product->categories()->detach();

        // 4. Hapus produk dari database
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus beserta media-nya.');
    }
}
