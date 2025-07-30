<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    public function index($id)
    {
        $shop = Shop::findOrFail($id);
        $products = Product::with('categories', 'media', 'variants', 'rating')->where('shop_id', $shop->id)->get();

        $categories = Category::all();
        return view('admin.management_products.product.index', compact('shop', 'products', 'categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $data = $request->validate([
            'name' => 'required|string',
            'categories' => 'nullable|array',
            'description' => 'nullable|string',
            'weight' => 'nullable|integer',
            'shop_id' => 'required|exists:shops,id',
        ]);

        $product = Product::create($data);
        // 3. Simpan kategori jika ada (opsional tergantung relasi)
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        // 4. Simpan File
        $folderName = 'P'.'/S' . $data['shop_id'] . '/P' . $product->id;

        if ($request->hasFile('media')) {
            $mediaMap = [];

            foreach ($request->file('media') as $file) {
                $type = Str::startsWith($file->getMimeType(), 'video') ? 'video' : 'image';

                $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => $folderName,
                    'resource_type' => $type,
                ]);

                $media = ProductMedia::create([
                    'product_id' => $product->id,
                    'file_path' => $uploaded['public_id'],
                    'file_type' => $uploaded['resource_type'],
                    'original_name' => $file->getClientOriginalName(),
                ]);

                // Simpan mapping nama file ke ID media
                $mediaMap[$file->getClientOriginalName()] = $media->id;
            }
        }

        // Simpan variants (setelah media terupload semua)
        if ($request->has('variants')) {
            $product->variants()->delete();

            foreach ($request->variants as $variant) {
                $product->variants()->create([
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'product_media_id' => $mediaMap[$variant['media_id']] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Produk berhasil disimpan!');
    }
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'categories' => 'nullable|array',
            'description' => 'nullable|string',
            'weight' => 'nullable|integer',
            'shop_id' => 'required|exists:shops,id',
        ]);
        $product = Product::findOrFail($id);
        $product->update($data);

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        $folderName = 'P' . '/S' . $data['shop_id'] . '/P' . $product->id;

        // Hapus media
        if ($request->has('deleted_media')) {
            foreach ($request->deleted_media as $mediaId) {
                $media = $product->media()->where('id', $mediaId)->first();
                if ($media) {
                    try {
                        Cloudinary::uploadApi()->destroy($media->file_path, [
                            'file_type' => $media->file_type ?? 'image', // image / video
                        ]);
                    } catch (\Exception $e) {
                        \Log::warning("Gagal menghapus Cloudinary ID: {$media->file_path} — {$e->getMessage()}");
                    }

                    $media->delete();
                }
            }
        }

        // Upload file baru
        if ($request->hasFile('media')) {
            $mediaMap = [];

            foreach ($request->file('media') as $file) {
                $type = Str::startsWith($file->getMimeType(), 'video') ? 'video' : 'image';

                $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => $folderName,
                    'resource_type' => $type,
                ]);

                $media = ProductMedia::create([
                    'product_id' => $product->id,
                    'file_path' => $uploaded['public_id'],
                    'file_type' => $uploaded['resource_type'],
                    'original_name' => $file->getClientOriginalName(),
                ]);

                // Simpan mapping nama file ke ID media
                $mediaMap[$file->getClientOriginalName()] = $media->id;
            }
        }

        // Simpan variants (setelah media terupload semua)
        if ($request->has('variants')) {
            $product->variants()->delete();

            foreach ($request->variants as $variant) {
                $product->variants()->create([
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'product_media_id' => $mediaMap[$variant['media_id']] ?? null,
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
        $product = Product::with('media', 'categories')->findOrFail($id);

        // 1. Hapus semua media dari Cloudinary
        foreach ($product->media as $media) {
            try {
                // Hapus file dari Cloudinary berdasarkan public_id
                Cloudinary::uploadApi()->destroy($media->file_path);
            } catch (\Exception $e) {
                \Log::warning("Gagal menghapus file Cloudinary: {$media->file_path} — {$e->getMessage()}");
            }

            // Hapus dari database
            $media->delete();
        }

        // 2. Hapus relasi kategori (jika ada)
        $product->categories()->detach();

        // 3. Hapus produk itu sendiri
        $product->delete();

        return redirect()->back()->with('success', 'Produk dan semua media berhasil dihapus dari Cloudinary dan database.');
    }
}
