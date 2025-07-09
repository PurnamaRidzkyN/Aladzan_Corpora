<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Tampilkan semua produk
        $categories = Category::all();
        return view('admin.management_products.category.index', compact('categories'));
    }

    public function create()
    {
        // Form tambah produk
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        // Validasi data produk
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Simpan data produk
        Category::create($request->all());

        // Redirect ke halaman kategori
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show($id)
    {
        // Tampilkan detail produk (opsional)
    }

    public function destroy($id)
    {
        // Hapus produk
        $category = Category::findOrFail($id);
        $category->delete();

        // Redirect ke halaman kategori
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
