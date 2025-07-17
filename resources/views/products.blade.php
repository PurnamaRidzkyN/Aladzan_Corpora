@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 pt-4" x-data="{ sort: '{{ request('sort', 'latest') }}' }">
    <!-- Judul Search / Kategori -->
   @if(request('q'))
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Hasil pencarian: <span class="text-blue-600">"{{ request('q') }}"</span></h2>
@elseif(isset($slug))
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Kategori: <span class="text-blue-600">{{ \App\Models\Category::where('slug', $slug)->first()->name ?? '-' }}</span></h2>
    
@else
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Semua Produk</h2>
@endif



   @php
    $currentRoute = Route::currentRouteName();
    $routeName = $currentRoute === 'category.show' ? 'category.show' : 'search';
    $routeParams = $routeName === 'category.show' ? ['slug' => $slug] : [];
@endphp

<!-- Filter Bar -->
<div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
    <!-- Sort Filter Buttons -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route($routeName, array_merge($routeParams, request()->except('page'), ['sort' => 'latest'])) }}"
            class="btn btn-sm flex items-center gap-1 {{ request('sort', 'latest') === 'latest' ? 'btn-primary' : 'btn-outline' }}">
            <i class="fa-solid fa-clock"></i> Terbaru
        </a>

        <a href="{{ route($routeName, array_merge($routeParams, request()->except('page'), ['sort' => 'terlaris'])) }}"
            class="btn btn-sm flex items-center gap-1 {{ request('sort') === 'terlaris' ? 'btn-primary' : 'btn-outline' }}">
            <i class="fa-solid fa-fire"></i> Terlaris
        </a>

        <a href="{{ route($routeName, array_merge($routeParams, request()->except('page'), ['sort' => 'rating'])) }}"
            class="btn btn-sm flex items-center gap-1 {{ request('sort') === 'rating' ? 'btn-primary' : 'btn-outline' }}">
            <i class="fa-solid fa-star"></i> Rating Tertinggi
        </a>

        <a href="{{ route($routeName, array_merge($routeParams, request()->except('page'), ['sort' => 'harga_tertinggi'])) }}"
            class="btn btn-sm flex items-center gap-1 {{ request('sort') === 'harga_tertinggi' ? 'btn-primary' : 'btn-outline' }}">
            <i class="fa-solid fa-arrow-up-wide-short"></i> Harga Tertinggi
        </a>

        <a href="{{ route($routeName, array_merge($routeParams, request()->except('page'), ['sort' => 'harga_terendah'])) }}"
            class="btn btn-sm flex items-center gap-1 {{ request('sort') === 'harga_terendah' ? 'btn-primary' : 'btn-outline' }}">
            <i class="fa-solid fa-arrow-down-wide-short"></i> Harga Terendah
        </a>
    </div>
</div>


    <!-- Produk Grid -->
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
                        @if ($i <= $avg)
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                    <span class="text-gray-400 ml-2">
                        ({{ $avgDecimal }} dari {{ $count }} ulasan) · {{ $product->sold ?? 0 }} terjual
                    </span>
                </div>

                <p class="text-xs text-gray-500 mb-1">oleh <span class="font-semibold">{{ $product->shops->name }}</span></p>
                <p class="text-blue-600 font-bold text-sm mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-gradient-primary w-full">
                    Lihat Detail
                </a>
            </div>
        @empty
            <p class="text-gray-500 text-sm col-span-full">Produk tidak ditemukan.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
