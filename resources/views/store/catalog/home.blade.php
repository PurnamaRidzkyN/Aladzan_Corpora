@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="">
        <!-- 🖼️ Banner -->
        <div class="relative rounded-2xl overflow-hidden shadow-md h-60 mb-10 bg-blue-100">
            <img src="https://source.unsplash.com/1200x400/?ecommerce,shopping"
                class="w-full h-full object-cover opacity-80 mix-blend-overlay" alt="Banner">

            <div class="absolute inset-0 bg-blue-900 bg-opacity-30 flex items-center justify-center">
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
                    class="card bg-white border border-blue-100 hover:shadow-lg hover:bg-blue-50 transition hover:-translate-y-1 rounded-xl">
                    <div class="card-body items-center text-center p-4">
                        <h3 class="font-semibold text-sm text-blue-800">{{ $cat->name }}</h3>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- 🛍️ Produk Rekomendasi -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Rekomendasi untuk Anda</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div
                    class="bg-white border border-blue-100 rounded-xl p-4 shadow-sm hover:shadow-md hover:bg-blue-50 transition">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img src="{{ cloudinary_url($product->media->first()?->file_path ?? 'https://source.unsplash.com/300x200/?product') }}"
                            alt="{{ $product->name }}" class="w-full sm:h-40  object-cover rounded-lg mb-3" />
                    </a>

                    <h3 class="text-sm font-semibold truncate text-blue-900 mb-1">{{ $product->name }}</h3>

                    @php
                        $avg = round($product->rating->rating ?? 0);
                        $avgDecimal = number_format($product->rating->rating ?? 0, 1);
                        $count = $product->rating->rating_count ?? 0;
                    @endphp

                    <div>
                        <!-- Baris bintang -->
                        <div class="text-yellow-500 text-sm">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $avg)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>

                        <!-- Baris deskripsi -->
                        <div class="flex flex-wrap text-slate-400 text-xs mt-1">
                            <span>
                                ({{ $avgDecimal }} dari {{ $count }} ulasan)
                            </span>
                            <span class="ml-2">
                                {{ $product->sold ?? 0 }} terjual
                            </span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 mb-1">
                        oleh <span class="font-semibold text-blue-700">{{ $product->shop->name }}</span>
                    </p>

                    @if ($product->variants->count())
                        <p class="text-blue-600 font-bold text-sm mb-3">
                            Rp {{ number_format($product->variants->first()->price, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-slate-400 text-sm mb-3">Belum ada harga</p>
                    @endif

                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-gradient-primary w-full">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endsection
