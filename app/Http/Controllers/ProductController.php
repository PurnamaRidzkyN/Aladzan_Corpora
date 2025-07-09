<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.management_products.product.index');

    }

    public function create()
    {
        // Form tambah produk
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        // Validasi data produk
        
    }

    public function show($id)
    {
        // Tampilkan detail produk (opsional)
    }

    public function destroy($id)
    {
        // Hapus produk

    }
}
