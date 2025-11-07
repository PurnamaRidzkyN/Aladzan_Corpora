@extends('layouts.app')

@section('title', 'Detail Produk' . ($product->name ?? ''))
@section('meta_description', $product->description ?? 'Jual kembali produk berkualitas dari ALADZAN dan dapatkan keuntungan menarik. Mulai bisnis reseller Anda sekarang.')

@section('content')
    <!-- Produk Utama -->
    <!-- Swiper CSS -->
    <main class="max-w-6xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
        <!-- Gambar Produk -->
        <div>
            <div x-data="{ preview: null, type: null }">
                <div class="swiper mySwiper w-full rounded-xl overflow-hidden">
                    <div class="swiper-wrapper">
                        @forelse ($product->media as $item)
                            @php
                                $isImage = $item->file_type === 'image';
                                $filePath = $item->file_path ?: 'productDefault_nawcx4';
                                $src = cloudinary_url(
                                    $filePath,
                                    $isImage ? 'image' : 'video',
                                    'w_800,h_800,c_fit,q_auto,f_auto',
                                );
                            @endphp

                            <div class="swiper-slide">
                                @if ($isImage)
                                    <img src="{{ $src }}" alt="{{ $item->original_name ?? 'Produk' }}"
                                        class="w-full h-[400px] object-contain cursor-pointer bg-black"
                                        @click="preview = '{{ $src }}'; type = 'image'">
                                @else
                                    <video class="w-full h-[400px] object-contain bg-black cursor-pointer" controls
                                        @click="preview = '{{ $src }}'; type = 'video'">
                                        <source src="{{ $src }}" type="video/mp4">
                                    </video>
                                @endif
                            </div>
                        @empty
                            {{-- Jika tidak ada media, tampilkan gambar default --}}
                            <div class="swiper-slide">
                                <img src="{{ cloudinary_url('productDefault_nawcx4', 'image', 'w_800,h_800,c_fit,q_auto,f_auto') }}"
                                    alt="No Product" class="w-full h-[400px] object-contain cursor-pointer bg-gray-100">
                            </div>
                        @endforelse


                    </div>

                    <!-- Navigasi -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>

                <!-- Modal Preview -->
                <template x-if="preview">
                    <div class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center">
                        <div class="relative max-w-3xl w-full p-4">
                            <template x-if="type === 'image'">
                                <img :src="preview" class="w-full max-h-[80vh] object-contain rounded" />
                            </template>
                            <template x-if="type === 'video'">
                                <video :src="preview" controls autoplay class="w-full max-h-[80vh] rounded"></video>
                            </template>
                            <button @click="preview = null"
                                class="absolute top-2 right-2 text-white text-2xl font-bold bg-black bg-opacity-50 px-3 py-1 rounded-full">
                                ✕
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script>
                document.addEventListener('alpine:init', () => {
                    new Swiper('.mySwiper', {
                        loop: {{ count($product->media) >= 3 ? 'true' : 'false' }},
                        loop: true,
                        pagination: {
                            el: '.swiper-pagination'
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    });
                });
            </script>


            <div class="mt-4 flex gap-4 items-center">
                <!-- Tombol -->
                <div x-data="assetModal()" class="inline-block">
                    <!-- Tombol Unduh -->
                    <button @click="openModal()"
                        class="bg-gray-200 text-sm px-4 py-2 rounded-xl hover:bg-gray-300 disabled:opacity-50"
                        :disabled="mediaCount === 0">
                        <i class="fa-solid fa-download mr-1"></i>
                        <span x-text="mediaCount === 0 ? 'Tidak Ada Aset' : 'Unduh Aset'"></span>
                    </button>

                    <!-- Modal Pilih Aset -->
                    <template x-if="openAssetModal">
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                            <div
                                class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all">

                                <!-- Header Modal -->
                                <div class="flex justify-between items-center px-6 py-4 border-b">
                                    <h2 class="text-lg font-semibold">Unduh Aset Produk</h2>
                                    <button @click="openAssetModal=false" class="text-gray-500 hover:text-gray-700">
                                        ✖
                                    </button>
                                </div>

                                <!-- Deskripsi Produk -->
                                <div class="px-6 py-4 border-b">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-gray-700 text-sm whitespace-pre-line">
                                                {{ $product->description ?? 'Tidak ada deskripsi' }}
                                            </p>
                                        </div>
                                        <button @click="copyDescription('{{ addslashes($product->description) }}')"
                                            class="ml-3 text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded border text-gray-600">
                                            <i class="fa-solid fa-copy mr-1"></i> Salin
                                        </button>
                                    </div>
                                </div>

                                <!-- Daftar Media -->
                                <div class="px-6 py-4 max-h-72 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @forelse($product->media as $media)
                                        <label class="relative border rounded-lg overflow-hidden group cursor-pointer">
                                            <input type="checkbox" value="{{ $media->id }}" x-model="selectedFiles"
                                                class="absolute top-2 left-2 z-10 w-4 h-4">

                                            @if (str_contains($media->file_type, 'video'))
                                                <!-- Thumbnail dari frame pertama video -->
                                                <img src="https://res.cloudinary.com/{{ config('cloudinary.cloud_name') }}/video/upload/so_0/{{ $media->file_path }}.jpg"
                                                    alt="Thumbnail Video"
                                                    class="w-full h-28 object-cover rounded-lg group-hover:opacity-80 transition">
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-white text-2xl font-bold">
                                                    ▶
                                                </div>
                                            @else
                                                <img src="{{ cloudinary_url($media->file_path, 'image', 'w_200,h_150,c_fill') }}"
                                                    class="w-full h-28 object-cover rounded-lg group-hover:opacity-80 transition">
                                            @endif

                                        </label>
                                    @empty
                                        <p class="col-span-full text-gray-500 text-sm text-center">Tidak ada media tersedia
                                        </p>
                                    @endforelse
                                </div>

                                <!-- Footer Actions -->
                                <div class="flex justify-end gap-2 px-6 py-4 border-t bg-gray-50">
                                    <button @click="openAssetModal=false"
                                        class="px-4 py-2 text-sm rounded-lg border hover:bg-gray-100">
                                        Batal
                                    </button>
                                    <button @click="downloadSelected()"
                                        class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                                        :disabled="selectedFiles.length === 0">
                                        <i class="fa-solid fa-download mr-1"></i> Unduh Terpilih
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <script>
                    function assetModal() {
                        return {
                            openAssetModal: false,
                            selectedFiles: [],
                            mediaCount: {{ $product->media->count() }},
                            openModal() {
                                if (this.mediaCount > 0) {
                                    this.openAssetModal = true;
                                }
                            },
                            downloadSelected() {
                                fetch("{{ route('media.downloadSelected') }}", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            files: this.selectedFiles
                                        })
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        data.files.forEach(file => {
                                            const link = document.createElement('a');
                                            link.href = file.url;
                                            link.download = file.name;
                                            console.log(file.name);
                                            document.body.appendChild(link);
                                            link.click();
                                            document.body.removeChild(link);
                                        });
                                    });
                            },
                            copyDescription(text) {
                                navigator.clipboard.writeText(text).then(() => {
                                    alert('Deskripsi berhasil disalin!');
                                });
                            }
                        };
                    }
                </script>


            </div>
        </div>

        <!-- Info Produk -->
        <div class="flex flex-col gap-4">
            <div class="text-sm text-gray-500 flex items-center justify-between mb-2">
                <div>
                    <span class="text-gray-400">Kategori:</span>
                    @foreach ($product->categories as $category)
                        <a href="" class="text-blue-600 hover:underline ml-1">{{ $category->name }}</a>
                    @endforeach
                </div>

                <a href="{{ route('shop.show', $product->shop->slug) }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg border border-sky-200 bg-white shadow-sm hover:shadow-md transition">

                    <!-- Gambar toko -->
                    <img src="{{ cloudinary_url($product->shop->img_path ?? 'productDefault_nawcx4') }}" alt="Logo Toko"
                        class="w-10 h-10 rounded-full object-cover border border-blue-200">

                    <!-- Info toko -->
                    <div>
                        <p class="text-sm font-semibold text-gray-800 leading-4">{{ $product->shop->name }}</p>
                        <p class="text-xs text-gray-500">Lihat toko</p>
                    </div>
                </a>
            </div>


            <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
            @if ($rating && $rating->rating_count > 0)
                @php
                    $fullStars = floor($rating->rating);
                    $halfStar = $rating->rating - $fullStars >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                <div class="flex items-center text-sm text-yellow-400">
                    {{-- Full Stars --}}
                    @for ($i = 0; $i < $fullStars; $i++)
                        <span>★</span>
                    @endfor

                    {{-- Half Star --}}
                    @if ($halfStar)
                        <span class="text-yellow-300">★</span>
                    @endif

                    {{-- Empty Stars --}}
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <span class="text-gray-300">★</span>
                    @endfor

                    {{-- Rating Text --}}
                    <span class="text-gray-600 ml-2">
                        ({{ number_format($rating->rating, 1) }} dari {{ $rating->rating_count }} ulasan)
                    </span>
                </div>
            @else
                <p class="text-sm text-gray-500">Belum ada rating.</p>
            @endif


            @if ($product->variants->count())
                <p class="text-2xl text-blue-600 font-bold">
                    Rp {{ number_format($product->variants->first()->price, 0, ',', '.') }}
                </p>
            @else
                <p class="text-2xl text-gray-500">Belum ada harga</p>
            @endif
            <div class="text-sm text-gray-500">
                Terjual: <span class="font-semibold text-green-600">{{ $product->sold }}</span>
            </div>

            <div x-data="cartForm({{ $product->toJson() }})">
                <!-- Tombol Aksi -->
                <div class="flex gap-3 mb-4">
                    <!-- Tombol Tambah ke Keranjang -->
                    <label for="modal_keranjang" class="w-1/2 btn btn-sm btn-gradient-primary rounded-xl"
                        @click="setAction('cart')">
                        <i class="fas fa-shopping-cart mr-1"></i> Tambah ke Keranjang
                    </label>

                    <!-- Tombol Beli Sekarang -->
                    <label for="modal_keranjang" class="w-1/2 btn btn-sm btn-gradient-success rounded-xl"
                        @click="setAction('buy_now')">
                        Beli Sekarang
                    </label>
                </div>

                <!-- Modal -->
                <input type="checkbox" id="modal_keranjang" class="modal-toggle" />
                <div class="modal" x-data="{
                    selectedImage: '',
                    selectVariant(variant) {
                        this.selectedVariant = variant;
                        this.quantity = 1;
                
                        // Cari media berdasarkan ID di product.media
                        if (variant.product_media_id) {
                            const media = product.media.find(m => m.id === variant.product_media_id);
                            this.selectedImage = media ?
                                cloudinaryUrl(media.file_path) :
                                (product.media.length ? cloudinaryUrl(product.media[0].file_path) : 'https://via.placeholder.com/300x200?text=No+Image');
                        } else {
                            // Jika varian tidak punya media, fallback ke media produk
                            this.selectedImage = product.media.length ?
                                cloudinaryUrl(product.media[0].file_path) :
                                'https://via.placeholder.com/300x200?text=No+Image';
                        }
                    }
                }">

                    <div class="modal-box max-w-md w-full p-6 rounded-xl">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-800" x-text="product.name"></h2>
                                <p class="text-blue-600 font-semibold mt-1">
                                    Rp <span x-text="formatPrice(selectedVariant?.price || 0)"></span>
                                </p>
                                <p class="text-sm text-yellow-500 flex items-center gap-1">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span x-text="Number(product.rating?.rating || 0).toFixed(1)"></span>
                                </p>
                            </div>
                            <label for="modal_keranjang" class="btn btn-sm btn-circle btn-ghost text-xl">✕</label>
                        </div>

                        <!-- Gambar Utama -->
                        <div class="mb-3">
                            <img :src="selectedImage || (product.media.length ? cloudinaryUrl(product.media[0].file_path) :
                                'https://via.placeholder.com/300x200?text=No+Image')"
                                alt="Gambar Produk"
                                class="w-full h-40 object-cover rounded-lg border transition duration-300">
                        </div>

                        <!-- Form -->
                        <form method="POST" action="{{ route('product.handleAction') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_variant_id" :value="selectedVariant?.id">
                            <input type="hidden" name="action" :value="modalAction">

                            <!-- Varian -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-700">Pilih Varian</label>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="variant in product.variants" :key="variant.id">
                                        <button type="button" class="btn btn-sm"
                                            :class="variant.id === selectedVariant?.id ? 'btn-gradient-primary' : 'btn-outline'"
                                            @click="selectVariant(variant)">
                                            <span x-text="variant.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-700">Jumlah</label>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="btn btn-sm" @click="decrement()">−</button>
                                    <input type="number" name="quantity" x-model="quantity"
                                        class="input input-bordered w-20 text-center" min="1" />
                                    <button type="button" class="btn btn-sm" @click="increment()">+</button>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="text-sm font-medium text-gray-700">
                                Total Harga:
                                <span class="text-blue-600 font-bold">
                                    Rp <span x-text="formatPrice(totalPrice())"></span>
                                </span>
                            </div>

                            <!-- Aksi -->
                            <div class="flex justify-end gap-2 pt-2">
                                <label for="modal_keranjang" class="btn btn-gradient-neutral">Batal</label>
                                <button type="submit" class="btn btn-gradient-primary" :disabled="!selectedVariant"
                                    x-text="modalAction === 'buy_now' ? 'Beli Sekarang' : 'Tambah ke Keranjang'">
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    function cloudinaryUrl(path) {
                        return `{{ cloudinary_url('') }}`.replace(/\/$/, '') + '/' + path;
                    }
                </script>

            </div>

            <form action="{{ route('favorite.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <button type="submit"
                    class="btn-gradient-neutral w-full mt-2 flex items-center justify-center gap-2 rounded-xl">
                    <i class="fa-regular fa-heart text-base"></i> Simpan Produk Ini
                </button>
            </form>
            @php
                $wa = $wa ? preg_replace('/^0/', '62', $wa) : null;

                $productUrl = route('product.show', $product->slug);
                $defaultMessage = "Halo, saya ingin bertanya tentang produk *{$product->name}*. Produk di sini: {$productUrl}";
                $waLink = $wa ? "https://wa.me/{$wa}?text=" . urlencode($defaultMessage) : null;
            @endphp

            @if ($waLink)
                <a href="{{ $waLink }}" target="_blank"
                    class="btn-gradient-warning w-full mt-2 flex items-center justify-center gap-2 rounded-xl">
                    <i class="fab fa-whatsapp text-base"></i> Tanya Produk Ini
                </a>
            @else
                <span class="text-red-500">Nomor WhatsApp belum tersedia.</span>
            @endif

        </div>
    </main>

    <!-- Deskripsi -->
    <section class="max-w-6xl mx-auto px-6 mt-12">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi Produk</h2>
        <p class="text-gray-600 text-sm leading-relaxed">
            {!! nl2br(e($product->description)) !!}
        </p>
    </section>

    <!-- Ulasan -->
    <section class="max-w-6xl mx-auto px-6 mt-12 bg-gray-50 rounded-xl p-6 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ulasan Pembeli</h2>
        <div class="flex items-center gap-4 mb-4">
            <div class="text-2xl font-bold">{{ number_format($rating->rating ?? 0, 1) }}</div>
            <div class="flex items-center">
                @php
                    $filled = round($rating->rating ?? 0);
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= $filled ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.211c.969 0 1.371 1.24.588 1.81l-3.405 2.472a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.538 1.118L10 13.348l-3.405 2.472c-.783.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.574 9.393c-.783-.57-.38-1.81.588-1.81h4.211a1 1 0 00.95-.69l1.286-3.966z" />
                    </svg>
                @endfor
            </div>
            <div class="text-sm text-gray-500">Dari {{ $rating->rating_count ?? 0 }} ulasan</div>
        </div>
        <div class="space-y-6">
            @forelse($latestReviews as $review)
                <div class="bg-white p-5 rounded-xl shadow-sm border">
                    {{-- Header user --}}
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <!-- Avatar -->
                            <img src="{{ cloudinary_url($review->reseller->pfp_path, 'image', 'w_200,h_200,c_fill') }}"
                                alt="{{ $review->reseller->name }}" class="w-8 h-8 roundedc-full object-cover">

                            <!-- Nama Reseller -->
                            <span class="font-semibold text-gray-800 text-sm">{{ $review->reseller->name }}</span>
                        </div>

                        <!-- Rating -->
                        <div class="text-yellow-400 text-sm">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>
                    </div>

                    {{-- Komentar user --}}
                    <p class="text-gray-700 text-sm leading-relaxed">
                        {{ $review->comment }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $review->reseller->name ?? 'user' }} • {{ $review->created_at->format('d M Y') }}
                    </p>
                    {{-- Balasan admin --}}
                    @if (!empty($review->reply))
                        <div class="mt-3 pl-4 border-l-4 border-blue-300 bg-blue-50 p-3 rounded-md">
                            <div class="flex items-center gap-2 mb-1">

                                <span class="font-semibold text-blue-800 text-xs">Balasan Admin
                                    {{ $review->admin->name }}</span>
                            </div>
                            <p class="text-gray-700 text-sm">{{ $review->reply }}</p>
                            @if ($review->reply_at)
                                <span class="text-gray-500 text-xs">
                                    Dibalas pada {{ \Carbon\Carbon::parse($review->reply_at)->format('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada ulasan.</p>
            @endforelse
            <label for="modal_review" class="text-blue-600 text-sm hover:underline cursor-pointer mt-2 block">
                Lihat semua ulasan
            </label>

        </div>
    </section>

    <!-- Produk Serupa -->
    <section class="max-w-6xl mx-auto px-6 mt-12">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Produk dari toko yang sama</h2>
        <div class="overflow-x-auto">
            <div class="flex gap-4">
                @foreach ($relatedProducts as $relatedProduct)
                    <div
                        class="flex-none w-56 bg-white border border-blue-100 rounded-xl p-3 shadow-sm hover:shadow-md hover:bg-blue-50 transition">
                        <a href="{{ route('product.show', $relatedProduct->slug) }}">
                            <img src="{{ cloudinary_url($relatedProduct->media->first()?->file_path ?? 'productDefault_nawcx4') }}"
                                alt="{{ $relatedProduct->name }}" class="w-full h-32 object-cover rounded-lg mb-2" />
                        </a>

                        <h3 class="text-sm font-semibold truncate text-blue-900 mb-1">{{ $relatedProduct->name }}</h3>

                        @php
                            $avg = round($relatedProduct->rating->rating ?? 0);
                            $avgDecimal = number_format($relatedProduct->rating->rating ?? 0, 1);
                            $count = $relatedProduct->rating->rating_count ?? 0;
                        @endphp

                        <div>
                            <!-- Bintang -->
                            <div class="text-yellow-500 text-xs">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $avg)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>

                            <!-- Rating & terjual -->
                            <div class="text-[11px] text-slate-400">
                                ({{ $avgDecimal }}) · {{ $relatedProduct->sold ?? 0 }} terjual
                            </div>
                        </div>

                        <p class="text-[11px] text-slate-500 mt-1 truncate">
                            oleh <span class="font-semibold text-blue-700">{{ $relatedProduct->shop->name }}</span>
                        </p>

                        @if ($relatedProduct->variants->count())
                            <p class="text-blue-600 font-bold text-sm mt-1">
                                Rp {{ number_format($relatedProduct->variants->first()->price, 0, ',', '.') }}
                            </p>
                        @else
                            <p class="text-slate-400 text-sm">Belum ada harga</p>
                        @endif

                        <a href="{{ route('product.show', $relatedProduct->slug) }}"
                            class="btn btn-sm btn-gradient-primary w-full mt-2">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </section>

    <!-- Modal DaisyUI -->
    <input type="checkbox" id="modal_review" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-xl p-6"
            x-data="{ selectedStar: 0 }">

            <!-- Tombol X -->
            <label for="modal_review" class="btn btn-sm btn-circle absolute right-3 top-3">✕</label>

            <!-- Header: Rating summary -->
            <div class="flex items-center gap-4 mb-4">
                <div class="text-2xl font-bold">{{ number_format($rating->rating ?? 0, 1) }}</div>
                <div class="flex items-center">
                    @php
                        $filled = round($rating->rating ?? 0);
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $filled ? 'text-yellow-400' : 'text-gray-300' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.211c.969 0 1.371 1.24.588 1.81l-3.405 2.472a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.538 1.118L10 13.348l-3.405 2.472c-.783.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.574 9.393c-.783-.57-.38-1.81.588-1.81h4.211a1 1 0 00.95-.69l1.286-3.966z" />
                        </svg>
                    @endfor
                </div>
                <div class="text-sm text-gray-500">Dari {{ $rating->rating_count ?? 0 }} ulasan</div>
            </div>

            <!-- Filter bintang -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button @click="selectedStar = 0" :class="selectedStar === 0 ? 'btn-gradient-primary' : 'btn-outline'"
                    class="btn btn-sm  ">Semua</button>
                @foreach ([5, 4, 3, 2, 1] as $star)
                    <button @click="selectedStar = {{ $star }}"
                        :class="selectedStar === {{ $star }} ? 'btn-gradient-primary' : 'btn-outline'"
                        class="btn btn-sm  flex items-center gap-1">
                        @for ($i = 0; $i < $star; $i++)
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.211c.969 0 1.371 1.24.588 1.81l-3.405 2.472a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.538 1.118L10 13.348l-3.405 2.472c-.783.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.574 9.393c-.783-.57-.38-1.81.588-1.81h4.211a1 1 0 00.95-.69l1.286-3.966z" />
                            </svg>
                        @endfor
                    </button>
                @endforeach
            </div>

            <!-- List Review -->
            @foreach ($product->review as $review)
                <div x-show="selectedStar === 0 || selectedStar === {{ $review->rating }}"
                    class="bg-white p-4 rounded-xl shadow-sm border mb-4">

                    {{-- Header user --}}
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-sm font-bold text-blue-800">
                                {{ strtoupper(substr($review->reseller->name ?? 'AN', 0, 2)) }}
                            </div>
                            <span class="font-semibold text-gray-800 text-sm">
                                {{ $review->reseller->name ?? 'Anonim' }}
                            </span>
                        </div>
                        <div class="text-yellow-400 text-sm">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>
                    </div>

                    {{-- Komentar user --}}
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $review->comment }} </p>
                    <p class="text-xs text-gray-400">
                        {{ $review->reseller->name ?? 'user' }} • {{ $review->created_at->format('d M Y') }}
                    </p>
                    {{-- Balasan admin --}}
                    @if (!empty($review->reply))
                        <div class="mt-3 pl-4 border-l-4 border-blue-300 bg-blue-50 p-3 rounded-md">
                            <div class="flex items-center gap-2 mb-1">

                                <span class="font-semibold text-blue-800 text-xs">Balasan Admin
                                    {{ $review->admin->name }}</span>
                            </div>
                            <p class="text-gray-700 text-sm">{{ $review->reply }}</p>
                            @if ($review->reply_at)
                                <span class="text-gray-500 text-xs">
                                    Dibalas pada {{ \Carbon\Carbon::parse($review->reply_at)->format('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                    @endif

                </div>
            @endforeach



        </div>
    </div>

    <script>
        function cartForm(product) {
            return {
                product,
                quantity: 1,
                selectedVariant: product.variants[0] || null,
                modalAction: 'cart', // default

                setAction(action) {
                    this.modalAction = action;
                },

                selectVariant(variant) {
                    this.selectedVariant = variant;
                },

                increment() {
                    this.quantity++;
                },

                decrement() {
                    if (this.quantity > 1) this.quantity--;
                },

                totalPrice() {
                    return this.selectedVariant ? this.quantity * this.selectedVariant.price : 0;
                },

                formatPrice(value) {
                    return value.toLocaleString('id-ID');
                }
            };
        }
    </script>

@endsection
