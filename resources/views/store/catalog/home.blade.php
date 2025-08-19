@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div x-data="ratingModal(@json($order ? $order->id : null))">
        <template x-if="open">
            <div
                class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 transition-opacity duration-300">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-transform duration-300 scale-95"
                    x-transition:enter="scale-95 opacity-0" x-transition:enter-start="scale-95 opacity-0"
                    x-transition:enter-end="scale-100 opacity-100" x-transition:leave="scale-100 opacity-100"
                    x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0">

                    <h2 class="text-2xl font-semibold text-center mb-2 text-gray-800">Kritik & Saran untuk Web Kami</h2>
                    <p class="text-center text-gray-600 mb-4 text-sm">
                        Nilai pengalamanmu dan berikan saran untuk meningkatkan layanan kami.
                    </p>
                    <form method="POST" action="{{ route('web-rating.store') }}">
                        @csrf
                        <input type="hidden" name="rating" :value="rating">

                        <!-- Bintang Interaktif -->
                        <div class="flex justify-center mb-2 space-x-2">
                            <template x-for="star in 5" :key="star">
                                <i class="fa fa-star cursor-pointer text-3xl transition-transform duration-200"
                                    :class="{
                                        'text-yellow-400 scale-125': star <= (hover || rating),
                                        'text-gray-300': star > (
                                            hover || rating)
                                    }"
                                    @click="rating = star" @mouseover="hover = star" @mouseleave="hover = 0">
                                </i>
                            </template>
                        </div>

                        <!-- Label rating -->
                        <div class="text-center mb-4 font-medium text-gray-700 text-sm" x-text="ratingLabel()"></div>

                        <!-- Komentar -->
                        <textarea name="comment" x-model="comment" placeholder="Tulis komentar (opsional)"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none mb-4"></textarea>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="open = false"
                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">Lain
                                Kali</button>
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition">Beri
                                Rating</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <script>
        function ratingModal(orderId) {
            return {
                open: orderId ? true : false,
                rating: 5,
                hover: 0,
                comment: '',
                orderId: orderId,
                ratingLabel() {
                    switch (this.rating) {
                        case 1:
                            return 'Sangat Buruk';
                        case 2:
                            return 'Buruk';
                        case 3:
                            return 'Cukup';
                        case 4:
                            return 'Baik';
                        case 5:
                            return 'Sangat Baik';
                        default:
                            return '';
                    }
                }
            }
        }
    </script>

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

                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-gradient-primary w-full">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endsection
