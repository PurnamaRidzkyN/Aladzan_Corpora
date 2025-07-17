@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Toko Header -->
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ $shop->name }}</h1>
        <p class="text-sm text-gray-500">{{ $shop->description ?? 'Tidak ada deskripsi toko.' }}</p>
    </div>

    <!-- Filter & Sort -->
    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
        <!-- Kategori -->
        <div class="flex flex-wrap gap-2 mb-4">
    {{-- Tombol "Semua" --}}
    <a href="{{ route('shop.show', ['slug' => $shop->slug]) }}"
        class="badge px-3 py-2 text-sm {{ request('category') ? 'badge-outline' : 'gradient-primary' }}">
        Semua
    </a>

    {{-- Tombol per kategori --}}
    @foreach ($categories as $cat)
        <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'category') + ['category' => $cat->slug])) }}"
            class="badge badge-outline px-3 py-2 text-sm {{ request('category') === $cat->slug ? 'gradient-primary' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>

        <!-- Sort Buttons -->
        <div class="flex flex-wrap gap-2">
            @php $sort = request('sort', 'latest'); @endphp
            <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'latest'])) }}"
               class="btn btn-sm {{ $sort === 'latest' ? 'gradient-primary' : 'btn-outline' }}">
               <i class="fa fa-clock mr-1"></i>Terbaru
            </a>
            <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'terlaris'])) }}"
               class="btn btn-sm {{ $sort === 'terlaris' ? 'gradient-primary' : 'btn-outline' }}">
               <i class="fa fa-fire mr-1"></i>Terlaris
            </a>
            <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'rating'])) }}"
               class="btn btn-sm {{ $sort === 'rating' ? 'gradient-primary' : 'btn-outline' }}">
               <i class="fa fa-star mr-1"></i>Rating
            </a>
            <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'harga_tertinggi'])) }}"
               class="btn btn-sm {{ $sort === 'harga_tertinggi' ? 'gradient-primary' : 'btn-outline' }}">
               <i class="fa fa-arrow-up mr-1"></i>Harga Tertinggi
            </a>
            <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'harga_terendah'])) }}"
               class="btn btn-sm {{ $sort === 'harga_terendah' ? 'gradient-primary' : 'btn-outline' }}">
               <i class="fa fa-arrow-down mr-1"></i>Harga Terendah
            </a>
        </div>
    </div>

    <!-- Produk List -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow-lg transition">
                <a href="{{ route('product.show', $product->slug) }}">
                    <img src="{{ 'https://drive.google.com/thumbnail?id=' . $product->media->first()?->file_path ?? 'https://source.unsplash.com/300x200/?product' }}"
                         alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-lg mb-3" />
                </a>

                <h3 class="text-sm font-semibold truncate text-gray-900 mb-1">{{ $product->name }}</h3>

                @php
                    $avg = round($product->rating->rating ?? 0);
                    $avgDecimal = number_format($product->rating->rating ?? 0, 1);
                    $count = $product->rating->rating_count ?? 0;
                @endphp

                <div class="flex items-center text-xs text-yellow-500 mb-1">
                    @for ($i = 1; $i <= 5; $i++)
                        {!! $i <= $avg ? '&#9733;' : '&#9734;' !!}
                    @endfor
                    <span class="text-gray-400 ml-2">({{ $avgDecimal }} dari {{ $count }} ulasan) · {{ $product->sold ?? 0 }} terjual</span>
                </div>

                <p class="text-blue-600 font-bold text-sm mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-gradient-primary w-full">
                    Lihat Detail
                </a>
            </div>
        @empty
            <p class="text-gray-500 text-sm col-span-full">Tidak ada produk ditemukan di toko ini.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
