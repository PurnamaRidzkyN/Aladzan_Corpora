@extends('layouts.app')

@section('title', 'Toko - ' . $shop->name)
@section('meta_description', $shop->description ?? 'Temukan berbagai produk menarik di toko kami. Mulai dari elektronik, fashion, hingga kebutuhan sehari-hari. Belanja sekarang dan nikmati penawaran terbaik!')
@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Toko Header -->
    <!-- Container utama -->
<div class="bg-blue-50 py-8 px-4 rounded-xl shadow-inner space-y-8">

    <!-- Header toko -->
    <div class="flex items-center gap-4">
        <!-- Gambar toko -->
        <img src="{{ cloudinary_url($shop->img_path ?? 'productDefault_mpgglw') }}" alt="Foto Toko"
            class="w-24 h-24 rounded-full object-cover border-4 border-blue-300 shadow">

        <!-- Info toko -->
        <div class="text-left">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800">{{ $shop->name }}</h1>
            <p class="text-sm text-gray-600 mt-1 max-w-md">
                {{ $shop->description ?? 'Tidak ada deskripsi toko.' }}
            </p>
        </div>
    </div>

    <!-- Video toko di bawah header -->
  @if ($shop->video_path !== null)
    <div class="w-full max-w-2xl mx-auto rounded-xl overflow-hidden shadow-md">
        <div class="relative" style="padding-bottom: 56.25%;">
            <iframe class="absolute top-0 left-0 w-full h-full rounded-xl"
                src="https://www.youtube-nocookie.com/embed/{{ $shop->video_path  }}?rel=0&modestbranding=1&showinfo=0&autoplay=0"
                title="Video Toko"
                frameborder="0"
                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    </div>
    @endif
</div>

        <!-- Filter & Sort -->
        <div class="flex flex-col gap-4 mb-6">
            <!-- Kategori -->
            <div class="overflow-x-auto md:overflow-visible scrollbar-hide">
                <div class="flex gap-2 min-w-max md:min-w-0 md:flex-wrap">
                    {{-- Tombol "Semua" --}}
                    <a href="{{ route('shop.show', ['slug' => $shop->slug]) }}"
                        class="badge px-3 py-2 text-sm hover:shadow-md hover:bg-blue-50 {{ request('category') ? 'badge-outline' : 'bg-gradient-to-r from-sky-100 to-blue-100 text-blue-700 ' }}">
                        Semua
                    </a>

                    {{-- Tombol per kategori --}}
                    @foreach ($categories as $cat)
                        <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'category') + ['category' => $cat->slug])) }}"
                            class="badge badge-outline px-3 py-2 text-sm hover:shadow-md hover:bg-blue-50 {{ request('category') === $cat->slug ? 'bg-gradient-to-r from-sky-100 to-blue-100 text-blue-700 ' : '' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Sort Buttons -->
            <div class="overflow-x-auto md:overflow-visible scrollbar-hide">
                <div class="flex gap-2 min-w-max md:min-w-0 md:flex-wrap">
                    @php $sort = request('sort', 'latest'); @endphp
                    <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'latest'])) }}"
                        class="btn btn-sm {{ $sort === 'latest' ? 'btn-gradient-primary ' : 'btn-outline' }}">
                        <i class="fa fa-clock mr-1"></i>Terbaru
                    </a>
                    <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'terlaris'])) }}"
                        class="btn btn-sm {{ $sort === 'terlaris' ? 'btn-gradient-primary ' : 'btn-outline' }}">
                        <i class="fa fa-fire mr-1"></i>Terlaris
                    </a>
                    <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'rating'])) }}"
                        class="btn btn-sm {{ $sort === 'rating' ? 'btn-gradient-primary ' : 'btn-outline' }}">
                        <i class="fa fa-star mr-1"></i>Rating
                    </a>
                    <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'harga_tertinggi'])) }}"
                        class="btn btn-sm {{ $sort === 'harga_tertinggi' ? 'btn-gradient-primary ' : 'btn-outline' }}">
                        <i class="fa fa-arrow-up mr-1"></i>Harga Tertinggi
                    </a>
                    <a href="{{ route('shop.show', array_merge(['slug' => $shop->slug], request()->except('page', 'sort') + ['sort' => 'harga_terendah'])) }}"
                        class="btn btn-sm {{ $sort === 'harga_terendah' ? 'btn-gradient-primary ' : 'btn-outline' }}">
                        <i class="fa fa-arrow-down mr-1"></i>Harga Terendah
                    </a>
                </div>
            </div>
        </div>


        <!-- Produk List -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <div
                    class="bg-white border border-blue-100 rounded-xl p-4 shadow-sm hover:shadow-md hover:bg-blue-50 transition">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img src="{{ cloudinary_url($product->media->first()?->file_path ?? 'productDefault_mpgglw') }}"
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

                    <a href="{{ route('product.show', $product->slug) }}" class="btn-gradient-primary  w-full">
                        Lihat Detail
                    </a>
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
                        <p class="text-sm text-gray-500">Toko ini belum memiliki produk.</p>
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
