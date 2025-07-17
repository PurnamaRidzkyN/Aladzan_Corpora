@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <!-- Produk Utama --><!-- Swiper CSS -->

    <main class="max-w-6xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
        <!-- Gambar Produk -->
        <div>
            <div x-data="{ preview: null, type: null }">
                <div class="swiper mySwiper w-full rounded-xl overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach ($product->media as $item)
    @php
        $isImage = $item->file_type == 'image';
        $src = cloudinary_url($item->file_path, $item->file_type);
    @endphp

    <div class="swiper-slide">
        @if ($isImage)
            <img src="{{ $src }}" alt="{{ $item->original_name }}"
                 class="w-full h-[400px] object-contain cursor-pointer bg-white"
                 @click="preview = '{{ $src }}'; type = 'image'">
        @else
            <video class="w-full h-[400px] object-contain bg-black cursor-pointer" controls
                   @click="preview = '{{ $src }}'; type = 'video'">
                <source src="{{ $src }}" type="video/mp4">
            </video>
        @endif
    </div>
@endforeach

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
                <a href="{{ $product->media->first()?->image_url ?? '#' }}" download
                    class="bg-gray-200 text-sm px-4 py-2 rounded-xl hover:bg-gray-300">
                    <i class="fa-solid fa-download mr-1"></i> Unduh Gambar
                </a>
                @php
                    $url = route('product.show', $product->slug);
                    $text =
                        "📦 Produk: {$product->name}\n" .
                        '💰 Harga: Rp ' .
                        number_format($product->price, 0, ',', '.') .
                        "\n" .
                        '🏪 Toko: ' .
                        ($product->shop->name ?? '-') .
                        "\n" .
                        '📄 Deskripsi: ' .
                        strip_tags(Str::limit($product->description, 150)) .
                        "\n" .
                        "📷 Lihat produk: $url";

                    $waLink = 'https://wa.me/?text=' . urlencode($text);
                    $fbLink = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url);
                @endphp

                <div class="flex gap-3 mt-4">
                    <a href="{{ $waLink }}" target="_blank" class="text-green-500 text-xl hover:scale-110"
                        title="Bagikan ke WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>

                    <a href="{{ $fbLink }}" target="_blank" class="text-blue-600 text-xl hover:scale-110"
                        title="Bagikan ke Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                </div>

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
                    class="flex items-center gap-2 hover:bg-gray-100 px-3 py-1 rounded-lg transition">
                    <img src="{{ $product->shop->logo_url ?? 'https://via.placeholder.com/40x40.png?text=Toko' }}"
                        class="w-8 h-8 rounded-full object-cover border" />
                    <div>
                        <p class="text-sm font-semibold text-gray-800 leading-4">{{ $product->shop->name }}</p>
                        <p class="text-xs text-gray-500">Lihat toko</p>
                    </div>
                </a>
            </div>

            <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
            @if ($rating && $rating->rating_count > 0)
                <div class="flex items-center text-sm text-yellow-400">
                    ★★★★☆
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
                <div class="modal">
                    <div class="modal-box max-w-md w-full p-6 rounded-xl" >
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-800" x-text="product.name"></h2>
                                <p class="text-blue-600 font-semibold mt-1">Rp
                                    <span x-text="formatPrice(selectedVariant?.price || 0)"></span>
                                </p>
                                <p class="text-sm text-yellow-500 flex items-center gap-1">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span x-text="Number(product.rating?.rating || 0).toFixed(1)"></span>
                                </p>
                            </div>
                            <label for="modal_keranjang" class="btn btn-sm btn-circle btn-ghost text-xl">✕</label>
                        </div>

                        <!-- Form -->
                        <form method="POST" action="{{ route('product.handleAction') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_variant_id" :value="selectedVariant?.id">
                            <input type="hidden" name="action" :value="modalAction"> <!-- kirim aksi -->

                            <!-- Varian -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-700">Pilih Varian</label>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="variant in product.variants" :key="variant.id">
                                        <button type="button" class="btn btn-sm"
                                            :class="variant.id === selectedVariant?.id ? 'btn-primary' : 'btn-outline'"
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
                                <span class="text-blue-600 font-bold">Rp <span
                                        x-text="formatPrice(totalPrice())"></span></span>
                            </div>

                            <!-- Aksi -->
                            <div class="flex justify-end gap-2 pt-2">
                                <label for="modal_keranjang" class="btn">Batal</label>
                                <button type="submit" class="btn btn-primary" :disabled="!selectedVariant"
                                    x-text="modalAction === 'buy_now' ? 'Beli Sekarang' : 'Tambah ke Keranjang'">
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <form action="{{ route('favorite.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <button type="submit"
                    class="mt-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm px-6 py-2 rounded-xl w-full flex items-center justify-center gap-2 transition duration-200">
                    <i class="fa-regular fa-heart text-base"></i> Simpan Produk Ini
                </button>
            </form>

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
            @forelse($product->reviews as $review)
                <div class="bg-white p-5 rounded-xl shadow-sm border">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-sm font-bold text-blue-800">
                                {{ strtoupper(substr($review->reseller->name, 0, 2)) }}
                            </div>
                            <span class="font-semibold text-gray-800 text-sm">{{ $review->reseller->name }}</span>
                        </div>
                        <div class="text-yellow-400 text-sm">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        {{ $review->comment }}
                    </p>
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
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Produk Serupa</h2>
        <div class="overflow-x-auto scrollbar-hide">
            <div class="flex gap-5">
                @foreach ($products as $related)
                    <div
                        class="min-w-[220px] bg-white rounded-xl shadow-sm hover:shadow-md transition p-3 border border-gray-200">
                        <img src="{{ $related->media->first()?->image_url ?? 'https://via.placeholder.com/200x150' }}"
                            class="rounded-lg mb-3 w-full h-32 object-cover" />
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2">
                            {{ $related->name }}
                        </h3>
                        <div class="text-sm text-yellow-400 mt-1">
                            ★★★★☆ <span class="text-gray-500 text-xs">(4.5)</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-1">
                            Toko: <span class="text-gray-700 font-medium">{{ $related->shop->name }}</span>
                        </div>
                        <p class="text-sm text-blue-600 font-bold">Rp {{ number_format($related->price, 0, ',', '.') }}
                        </p>
                        <a href="{{ route('product.show', $related->slug) }}"
                            class="mt-2 w-full block bg-blue-600 text-white text-xs py-1.5 text-center rounded-md hover:bg-blue-700">
                            Lihat Produk
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Modal DaisyUI -->
    <input type="checkbox" id="modal_review" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box w-full max-w-2xl max-h-[90vh] overflow-y-auto relative" x-data="{ selectedStar: 0 }">

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
                <button @click="selectedStar = 0" :class="selectedStar === 0 ? 'btn-primary' : 'btn-outline'"
                    class="btn btn-sm">Semua</button>
                @foreach ([5, 4, 3, 2, 1] as $star)
                    <button @click="selectedStar = {{ $star }}"
                        :class="selectedStar === {{ $star }} ? 'btn-primary' : 'btn-outline'"
                        class="btn btn-sm flex items-center gap-1">
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
            @foreach ($product->reviews as $review)
                <div x-show="selectedStar === 0 || selectedStar === {{ $review->rating }}"
                    class="bg-white p-4 rounded-xl shadow-sm border mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-sm font-bold text-blue-800">
                                {{ strtoupper(substr($review->reseller->name ?? 'AN', 0, 2)) }}
                            </div>
                            <span
                                class="font-semibold text-gray-800 text-sm">{{ $review->reseller->name ?? 'Anonim' }}</span>
                        </div>
                        <div class="text-yellow-400 text-sm">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $review->comment }}</p>
                </div>
            @endforeach


        </div>
    </div>
    <input type="checkbox" id="modal_keranjang" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box max-w-md w-full p-6 rounded-xl" x-data="cartForm({{ $product->toJson() }})">
            <!-- Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800" x-text="product.name"></h2>
                    <p class="text-blue-600 font-semibold mt-1">Rp <span
                            x-text="formatPrice(selectedVariant?.price || 0)"></span></p>
                    <p class="text-sm text-yellow-500 flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>

                        <span x-text="Number(product.rating?.rating || 0).toFixed(1)"></span>

                    </p>
                </div>
                <label for="modal_keranjang" class="btn btn-sm btn-circle btn-ghost text-xl">✕</label>
            </div>

            <form method="POST" action="" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" :value="product.id">
                <input type="hidden" name="variant_id" :value="selectedVariant?.id">

                <!-- Varian -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Pilih Varian</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="variant in product.variants" :key="variant.id">
                            <button type="button" class="btn btn-sm"
                                :class="variant.id === selectedVariant?.id ? 'btn-primary' : 'btn-outline'"
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
                    <span class="text-blue-600 font-bold">Rp <span x-text="formatPrice(totalPrice())"></span></span>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Catatan</label>
                    <textarea name="note" class="textarea textarea-bordered w-full" placeholder="Contoh: warna bebas..."></textarea>
                </div>

                <!-- Aksi -->
                <div class="flex justify-end gap-2 pt-2">
                    <label for="modal_keranjang" class="btn">Batal</label>
                    <button type="submit" class="btn btn-primary" :disabled="!selectedVariant">Tambah ke
                        Keranjang</button>
                </div>
            </form>
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
