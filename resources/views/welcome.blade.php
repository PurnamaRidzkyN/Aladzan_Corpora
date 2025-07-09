@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<!-- ✅ Banner -->
<section class="max-w-7xl mx-auto px-6 mt-6">
  <div class="relative rounded-2xl overflow-hidden shadow-md h-60">
    <img src="https://source.unsplash.com/1200x400/?shopping,store" 
         class="w-full h-full object-cover" 
         alt="Banner Promo">

    <!-- Overlay + Text -->
    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
      <div class="text-center text-white">
        <h2 class="text-3xl font-bold drop-shadow-md">Belanja Hemat Hari Ini</h2>
        <p class="mt-2 text-sm font-light">Diskon spesial hanya untuk kamu!</p>
      </div>
    </div>
  </div>
</section>

<!-- ✅ Produk -->
<section class="max-w-7xl mx-auto px-6 mt-12">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">🛒 Produk Terbaru</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

    <!-- Produk Card -->
    <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all border border-gray-100">
      <div class="relative">
        <img src="https://source.unsplash.com/300x200/?product" 
             class="w-full h-40 object-cover rounded-xl mb-3" />
        <!-- Badge Baru -->
        <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow">
          Baru
        </span>
      </div>

      <h3 class="text-base font-semibold text-gray-900">Nama Produk</h3>

      <!-- Rating Dummy -->
      <div class="flex items-center text-yellow-400 text-xs mt-1 mb-2">
        ★★★★☆ <span class="text-gray-400 ml-2">(12)</span>
      </div>

      <p class="text-blue-600 font-bold mb-3">Rp 25.000</p>

      <button class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 rounded-xl transition">
        + Tambah ke Keranjang
      </button>
    </div>

    <!-- Tambahkan produk lain sesuai kebutuhan -->

  </div>
</section>

@endsection
