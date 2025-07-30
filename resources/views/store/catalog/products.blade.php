@extends('layouts.app')

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
                        <img src="{{ cloudinary_url($product->media->first()?->file_path ?? 'https://source.unsplash.com/300x200/?product') }}"
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
                <p class="text-gray-500 text-sm col-span-full">Produk tidak ditemukan.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
@endsection
