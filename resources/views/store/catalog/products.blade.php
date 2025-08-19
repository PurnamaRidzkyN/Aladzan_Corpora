@extends('layouts.app')

@section('title', 'Produk Katalog')
@section('content')
    <div x-data="{ sort: '{{ request('sort', 'latest') }}' }">
        <!-- Judul Search / Kategori -->
        @if (request('q'))
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Hasil pencarian: <span
                    class="text-blue-600">"{{ request('q') }}"</span></h2>
        @elseif(isset($slug))
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Kategori: <span
                    class="text-blue-600">{{ \App\Models\Category::where('slug', $slug)->first()->name ?? '-' }}</span></h2>
        @else
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Semua Produk</h2>
        @endif



        @php
            $currentRoute = Route::currentRouteName();
            $routeName = $currentRoute === 'category.show' ? 'category.show' : 'search';
            $routeParams = $routeName === 'category.show' ? ['slug' => $slug] : [];
        @endphp

        <!-- Filter Bar -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <!-- Sort Filter Buttons -->
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                @php
                    $sortOptions = [
                        'latest' => ['icon' => 'fa-clock', 'label' => 'Terbaru'],
                        'terlaris' => ['icon' => 'fa-fire', 'label' => 'Terlaris'],
                        'rating' => ['icon' => 'fa-star', 'label' => 'Rating Tertinggi'],
                        'harga_tertinggi' => ['icon' => 'fa-arrow-up-wide-short', 'label' => 'Harga Tertinggi'],
                        'harga_terendah' => ['icon' => 'fa-arrow-down-wide-short', 'label' => 'Harga Terendah'],
                    ];
                    $currentSort = request('sort', 'latest');
                @endphp

                <div class="w-full overflow-x-auto md:overflow-visible">
                    <div class="flex gap-2 w-max">
                        @foreach ($sortOptions as $key => $option)
                            <a href="{{ route($routeName, array_merge($routeParams, request()->except('page'), ['sort' => $key])) }}"
                                class="btn btn-sm flex items-center gap-1 transition-all duration-200 whitespace-nowrap
                  {{ $currentSort === $key ? 'btn-gradient-primary shadow-sm' : 'btn-outline hover:bg-blue-50 text-gray-700' }}">
                                <i class="fa-solid {{ $option['icon'] }}"></i> {{ $option['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Produk Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 ">
            @forelse ($products as $product)
                <div
                    class="rounded-xl border border-soft bg-white hover:bg-accent-light hover:shadow-md transition-all duration-200 group">
                    <a href="{{ route('product.show', $product->slug) }}" class="block p-4">
                        <img src="{{ cloudinary_url($product->media->first()?->file_path ?? 'productDefault_mpgglw') }}"
                            alt="{{ $product->name }}" class="w-full sm:h-40 object-cover rounded-lg mb-3" />

                        <h3 class="text-sm font-semibold truncate text-gray-900 mb-1">{{ $product->name }}</h3>

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


                        <p class="text-xs text-gray-500 mb-1">oleh <span
                                class="font-semibold">{{ $product->shop->name }}</span></p>
                        <p class="text-blue-600 font-bold text-sm mb-3">Rp
                            {{ number_format($product->variants->first()->price, 0, ',', '.') }}</p>
                    </a>

                    <div class="px-4 pb-4">
                        <a href="{{ route('product.show', $product->slug) }}"
                            class="btn btn-sm btn-gradient-primary w-full">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class=" col-span-full text-center py-10 w-full">
                    <div
                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-2.293 2.293a1 1 0 01-.707.293H6a1 1 0 01-.707-.293L3 13m17 0V17a2 2 0 01-2 2H6a2 2 0 01-2-2v-4" />
                        </svg>

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-9 4h10m-10 0a2 2 0 110-4h10a2 2 0 110 4m-10 0V20" />
                        </svg>
                        <p class="text-sm text-gray-500">Oops! Kami tidak menemukan produk yang kamu cari.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
@endsection
