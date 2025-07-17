@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="max-w-7xl mx-auto px-6 mt-6">
        <!-- 🖼️ Banner -->
        <div class="relative rounded-2xl overflow-hidden shadow-md h-60 mb-10">
            <img src="https://source.unsplash.com/1200x400/?ecommerce,shopping" class="w-full h-full object-cover"
                alt="Banner">

            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <div class="text-white text-center">
                    <h2 class="text-3xl font-bold drop-shadow-md">Temukan Produk Terbaik</h2>
                    <p class="text-sm mt-2">Belanja hemat dan nyaman bersama kami!</p>
                </div>
            </div>
        </div>

        <!-- 🔹 Kategori Produk -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Kategori</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 mb-10">
            @foreach ($categories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}"
                    class="card shadow-md border hover:shadow-lg transition hover:-translate-y-1 rounded-xl">
                    <div class="card-body items-center text-center p-4">
                        <h3 class="font-semibold text-sm text-gray-700">{{ $cat->name }}</h3>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- 🛍️ Produk Rekomendasi -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Rekomendasi untuk Anda</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow-lg transition">
                    <a href="">
                        <img src="{{ 'https://res.cloudinary.com/dpujlyn9x/image/upload/' . $product->media->first()?->file_path .'.jpg' ?? 'https://source.unsplash.com/300x200/?product' }}"
                            alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-lg mb-3" />
                    </a>

                    <h3 class="text-sm font-semibold truncate text-gray-900 mb-1">{{ $product->name }}</h3>

                    <!-- ⭐ Rating & Terjual -->
                    @php
                        $avg = round($product->rating->rating ?? 0); // Untuk bintang utuh (bulat)
                        $avgDecimal = number_format($product->rating->rating ?? 0, 1); // Angka desimal
                        $count = $product->rating->rating_count ?? 0;
                    @endphp

                    <div class="flex items-center text-xs text-yellow-500 mb-1">
                        {{-- ★ & ☆ --}}
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $avg)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor

                        {{-- Angka & jumlah ulasan --}}
                        <span class="text-gray-400 ml-2">
                            ({{ $avgDecimal }} dari {{ $count }} ulasan)
                            · {{ $product->sold ?? 0 }} terjual
                        </span>
                    </div>


                    <!-- 🏪 Nama Toko -->
                    <p class="text-xs text-gray-500 mb-1">oleh <span class="font-semibold">{{ $product->shop->name }}</span>
                    </p>

                    <!-- 💰 Harga -->
                    @if ($product->variants->count())
                        <p class="text-blue-600 font-bold text-sm mb-3">
                            Rp {{ number_format($product->variants->first()->price, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-gray-500 text-sm mb-3">Belum ada harga</p>
                    @endif

                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-gradient-primary w-full">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endsection
