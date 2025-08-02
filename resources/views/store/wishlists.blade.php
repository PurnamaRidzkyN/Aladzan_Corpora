@extends('layouts.app')

@section('content')
    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">❤️ Produk Favorit Saya</h2>

        @if ($wishlists->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @foreach ($wishlists as $wishlist)
                    @php
                        $product = $wishlist->product;
                        $avg = round($product->rating->rating ?? 0);
                        $avgDecimal = number_format($product->rating->rating ?? 0, 1);
                        $count = $product->rating->rating_count ?? 0;
                    @endphp

                    <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow-lg transition relative">
                        <a href="{{ route('product.show', $product->slug) }}">
                              <img src="{{ cloudinary_url($product->media->first()?->file_path ?? 'https://source.unsplash.com/300x200/?product') }}"
                            alt="{{ $product->name }}" class="w-full sm:h-40  object-cover rounded-lg mb-3" />
                        </a>

                        <h3 class="text-sm font-semibold truncate text-gray-900 mb-1">{{ $product->name }}</h3>

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
                            {{ number_format($product->variants->first()->price, 0, ',', '.') }}
                        </p>

                        <div class="flex gap-2 items-center">
                            <!-- Tombol Lihat Detail -->
                            <a href="{{ route('product.show', $product->slug) }}"
                                class="btn btn-sm btn-gradient-primary flex-1">
                                Lihat Detail
                            </a>

                            <!-- Tombol Modal -->
                            <label for="delete-wishlist-{{ $wishlist->id }}" class="btn btn-sm btn-gradient-error px-3">
                                <i class="fas fa-heart-broken"></i>
                            </label>
                        </div>

                        <!-- Modal Hapus Wishlist -->
                        <input type="checkbox" id="delete-wishlist-{{ $wishlist->id }}" class="modal-toggle" />
                        <div class="modal" role="dialog">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                <p class="py-4">Yakin ingin menghapus produk <strong>{{ $product->name }}</strong> dari
                                    favorit?</p>
                                <div class="modal-action">
                                    <form action="{{ route('favorite.destroy', $wishlist->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-gradient-error">Ya, Hapus</button>
                                    </form>
                                    <label for="delete-wishlist-{{ $wishlist->id }}" class="btn-gradient-neutral">Batal</label>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $wishlists->links() }}
            </div>
        @else
           <div class="mt-20 flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-200 rounded-xl">
    <div class="text-center text-gray-500 py-10">
        <i class="fas fa-heart text-5xl mb-4 text-gray-300"></i>
        <p class="text-sm">Belum ada produk yang disimpan di wishlist.</p>
        <a href="{{ route('home') }}" class="text-blue-600 hover:underline mt-2 inline-block">Lihat produk</a>
    </div>
</div>

        @endif
    </div>

@endsection
