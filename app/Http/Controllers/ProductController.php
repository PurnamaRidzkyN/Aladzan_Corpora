<?php

namespace App\Http\Controllers;

use ActivityHelper;
use App\Models\Shop;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Category;

use Illuminate\Support\Str;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Helpers\AdminActivityHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    public function index($id)
    {
        $shop = Shop::findOrFail($id);

        if (Auth::guard('admin')->user()->is_super_admin) {
            $products = Product::with('categories', 'media', 'variants', 'rating')->where('shop_id', $shop->id)->withTrashed()->get();
        } else {
            $products = Product::with('categories', 'media', 'variants', 'rating')->where('shop_id', $shop->id)->get();
        }

        $categories = Category::all();
        return view('admin.management_products.product.index', compact('shop', 'products', 'categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $data = $request->validate(
            [
                'name' => 'required|string',
                'categories' => 'nullable|array',
                'description' => 'nullable|string',
                'weight' => 'nullable|integer',
                'shop_id' => 'required|exists:shops,id',
            ],
            [
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa teks.',
                'categories.array' => 'Kategori harus berupa array.',
                'description.string' => 'Deskripsi harus berupa teks.',
                'weight.integer' => 'Berat harus berupa angka.',
                'shop_id.required' => 'Toko wajib dipilih.',
                'shop_id.exists' => 'Toko yang dipilih tidak tersedia.',
            ],
        );

        $product = Product::create($data);
        // 3. Simpan kategori jika ada (opsional tergantung relasi)
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        // 4. Simpan File
        $folderName = 'P' . '/S' . $data['shop_id'] . '/P' . $product->id;
        $mediaMap = [];
        if ($request->hasFile('media')) {
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
                    'product_media_id' => isset($variant['media_id']) ? data_get($mediaMap, $variant['media_id'], null) : null,
                ]);
            }
        }
        AdminActivityHelper::log('CREATE', 'products', $product->id, 'Menambahkan produk baru: ' . $product->name);
        return redirect()->back()->with('success', 'Produk berhasil disimpan!');
    }
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'categories' => 'nullable|array',
            'description' => 'nullable|string',
            'weight' => 'nullable|integer',
            'shop_id' => 'required|exists:shops,id',
        ]);

        $product = Product::findOrFail($id);

        // --- SIMPAN DATA ORIGINAL SEBELUM PERUBAHAN ---
        $originalProduct = $product->getOriginal();
        $oldCategories = $product->categories->pluck('id')->toArray();
        $oldVariants = $product->variants()->with('media')->get()->keyBy('id');
        $oldMedia = $product->media()->get()->keyBy('id');

        $logDetails = [];

        // --- UPDATE DATA PRODUK ---
        $product->update($validated);

        // Catat perubahan di field produk utama
        foreach ($product->getChanges() as $field => $newValue) {
            $oldValue = $originalProduct[$field] ?? null;
            $logDetails[] = ucfirst($field) . ": '{$oldValue}' → '{$newValue}'";
        }

        // --- UPDATE KATEGORI ---
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
            $newCategories = $product->categories->pluck('id')->toArray();

            if ($oldCategories != $newCategories) {
                $logDetails[] = 'Kategori: [' . implode(',', $oldCategories) . '] → [' . implode(',', $newCategories) . ']';
            }
        }

        // --- HAPUS MEDIA ---
        if ($request->has('deleted_media')) {
            foreach ($request->deleted_media as $mediaId) {
                if (isset($oldMedia[$mediaId])) {
                    $media = $oldMedia[$mediaId];
                    $logDetails[] = "Menghapus media: {$media->original_name}";

                    try {
                        Cloudinary::uploadApi()->destroy($media->file_path, [
                            'file_type' => $media->file_type ?? 'image',
                        ]);
                    } catch (\Exception $e) {
                        \Log::warning("Gagal menghapus Cloudinary ID: {$media->file_path} — {$e->getMessage()}");
                    }

                    $media->delete();
                }
            }
        }

        // --- UPLOAD MEDIA BARU ---
        $mediaMap = [];
        if ($request->hasFile('media')) {
            $folderName = 'P/S' . $validated['shop_id'] . '/P' . $product->id;

            foreach ($request->file('media') as $file) {
                $logDetails[] = 'Menambahkan media baru: ' . $file->getClientOriginalName();
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

                $mediaMap[$file->getClientOriginalName()] = $media->id;
            }
        }

        // --- HAPUS VARIAN ---
        if ($request->has('deleted_variants')) {
            foreach ($request->deleted_variants as $variantId) {
                if (isset($oldVariants[$variantId])) {
                    $logDetails[] = "Menghapus varian: {$oldVariants[$variantId]->name}";
                    $oldVariants[$variantId]->delete();
                }
            }
        }

        // --- UPDATE VARIAN ---
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                $variantId = $variantData['variant_id'] ?? null;

                $data = [
                    'name' => $variantData['name'],
                    'price' => $variantData['price'],
                    'product_media_id' => isset($variantData['media_id']) ? data_get($mediaMap, $variantData['media_id'], null) : null,
                ];

                if ($variantId) {
                    // Update variant existing
                    $variant = ProductVariant::find($variantId);
                    if ($variant) {
                        $oldVariant = $oldVariants[$variant->id] ?? $variant->getOriginal();
                        $variant->update($data);

                        foreach ($variant->getChanges() as $field => $newValue) {
                            $oldValue = $oldVariant[$field] ?? null;
                            $logDetails[] = "Varian [{$variant->id}] - {$field}: '{$oldValue}' → '{$newValue}'";
                        }
                    }
                } else {
                    // Tambah variant baru
                    $product->variants()->create($data);
                }
            }
        }

        // --- SIMPAN LOG ---
        $description = 'Mengubah informasi produk: ' . $product->name;
        if (!empty($logDetails)) {
            $description .= ' | Perubahan: ' . implode(', ', $logDetails);
        }

        AdminActivityHelper::log('UPDATE', 'products', $product->id, $description);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::with('media', 'categories')->findOrFail($id);

        // 3. Hapus produk itu sendiri
        AdminActivityHelper::log('DELETE', 'products', $product->id, 'Menghapus produk: ' . $product->name);

        $product->delete();

        return redirect()->back()->with('success', 'Produk dan semua media berhasil dihapus dari Cloudinary dan database.');
    }
    public function restore($id)
    {
        if (Auth::guard('admin')->user()->is_super_admin) {
            $product = Product::withTrashed()->findOrFail($id);
            $product->restore();
            return back()->with('success', 'Produk berhasil dikembalikan.');
        } else {
            return back()->with('error', 'Maaf, Anda tidak memiliki izin. Hanya Super Admin yang bisa memulihakan produk.');
        }
    }

    // Force delete (permanen)
    public function forceDelete($id)
    {
        if (Auth::guard('admin')->user()->is_super_admin) {
            $product = Product::withTrashed()->findOrFail($id);
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
            $product->forceDelete();
        } else {
            return back()->with('error', 'Maaf, Anda tidak memiliki izin. Hanya Super Admin yang bisa menghapus permanen produk.');
        }
        return back()->with('success', 'Produk dihapus permanen.');
    }
}
