@extends('layouts.dashboard')
@section('title', 'Semua Ulasan')
@php
    $title = 'Produk Toko ' . $product->shop->name;
    $breadcrumb = [
        ['label' => 'Manajemen Produk'],
        ['label' => 'List Toko', 'url' => route('shops.index')],
        ['label' => $product->shop->name, 'url' => route('shops.products.index', $product->shop->id)],
        ['label' => 'Ulasan Produk'],
    ];
@endphp
@section('content')
    <div x-data="replyModal()">
        <div x-data="{ selectedStar: 0 }" class="max-w-5xl mx-auto px-4 py-6 space-y-6">

            <h1 class="text-xl font-bold text-primary">Semua Ulasan</h1>

            <!-- Info Produk -->
            <div
                class="bg-white border p-4 rounded-xl shadow mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">

                    <div>
                        <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-500 mb-2">{{ $product->description }}</p>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm text-gray-600 font-medium">Kategori:</span>
                            @foreach ($product->categories as $category)
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                </div>
                <a href="{{ route('shops.products.index', $product->shop->id) }}" class="btn btn-gradient-neutral btn-sm sm:btn-md">←
                    Kembali</a>
            </div>
            <!-- Rangkuman -->
            <div class="flex items-center gap-4 mb-4">
                <div class="text-2xl font-bold">
                    {{ number_format($product->rating->rating, 1) }}
                </div>
                <div class="flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($product->rating->rating))
                            <span class="text-yellow-400">⭐</span>
                        @elseif($i - $product->rating_avg < 1)
                            <span class="text-yellow-400">☆</span>
                        @else
                            <span class="text-gray-300">☆</span>
                        @endif
                    @endfor
                </div>
                <div class="text-sm text-gray-500">Dari {{ $product->rating_count }} ulasan</div>
            </div>


            <!-- Filter Bintang -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button @click="selectedStar = 0" :class="selectedStar === 0 ? 'btn-gradient-primary' : 'btn-outline btn-gradient-neutral'"
                    class="btn btn-sm">
                    Semua
                </button>
                @foreach ([5, 4, 3, 2, 1] as $star)
                    <button @click="selectedStar = {{ $star }}"
                        :class="selectedStar === {{ $star }} ? 'btn-gradient-primary' : 'btn-outline btn-gradient-neutral'"
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

            <!-- Rangkuman -->
            <div class="flex items-center gap-4 mb-4">
                <div class="text-2xl font-bold">{{ $averageRating }}</div>
                <div class="flex items-center">
                    {!! str_repeat('⭐', floor($averageRating)) !!}
                    @if ($averageRating - floor($averageRating) >= 0.5)
                        ☆
                    @endif
                </div>
                <div class="text-sm text-gray-500">Dari {{ $totalReviews }} ulasan</div>
            </div>

            <!-- Daftar Ulasan -->
            <div class="space-y-4">
                @foreach ($ratings as $rating)
                    @php
                        $star = $rating->rating;
                        $userName = $rating->reseller->name ?? 'Pengguna';
                        $userInitial = strtoupper(substr($userName, 0, 1));
                        $comment = $rating->comment;
                    @endphp
                    <div x-show="selectedStar === 0 || selectedStar === {{ $star }}"
                        class="bg-white p-4 rounded-xl shadow-sm border">

                        {{-- Header user --}}
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-sm font-bold text-blue-800">
                                    {{ $userInitial }}
                                </div>
                                <span class="font-semibold text-gray-800 text-sm">{{ $userName }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-yellow-400 text-sm">
                                @for ($j = 0; $j < $star; $j++)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.211c.969 0 1.371 1.24.588 1.81l-3.405 2.472a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.538 1.118L10 13.348l-3.405 2.472c-.783.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.574 9.393c-.783-.57-.38-1.81.588-1.81h4.211a1 1 0 00.95-.69l1.286-3.966z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>

                        {{-- Komentar user --}}
                        <p class="text-gray-700 text-sm leading-relaxed">
                            {{ $comment }}
                        </p>
                        <div class="mt-2 flex justify-between items-center">
                            <p class="text-xs text-gray-400">
                                {{ $rating->reseller->name ?? 'user' }} • {{ $rating->created_at->format('d M Y') }}
                            </p>
                            @if ($rating->reply)
                                <button class="btn btn-xs btn-outline btn-gradient-neutral btn-warning"
                                    @click="openModal('{{ $rating->id }}', '{{ $rating->reply }}')">
                                    Edit Balasan
                                </button>
                            @else
                                <button class="btn btn-xs btn-outline  btn-gradient-primary"
                                    @click="openModal('{{ $rating->id }}')">
                                    Balas
                                </button>
                            @endif
                        </div>

                        {{-- Balasan admin --}}
                        @if ($rating->reply)
                            <div class="mt-3 pl-4 border-l-4 border-primary bg-gray-50 rounded">
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-semibold text-primary">{{ $rating->admin->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $rating->reply_at ? $rating->reply_at->format('d M Y H:i') : '' }}
                                    </p>
                                </div>
                                <p class="text-gray-700 text-sm mt-1">{{ $rating->reply }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>

        </div>
            {{-- Modal --}}
            <div x-show="show" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <div class="bg-white p-4 rounded shadow w-96" @click.away="closeModal">
                    <h2 class="text-lg font-bold mb-3" x-text="replyText ? 'Edit Balasan' : 'Balas Komentar'"></h2>
                    <form method="POST" :action="'{{ url('staff-only/reviews') }}/' + commentId + '/reply'">
                        @csrf
                        @method('PUT')
                        <textarea name="reply" class="textarea textarea-bordered w-full mb-3" placeholder="Tulis balasan..."
                            x-text="replyText"></textarea>

                        <div class="flex justify-end gap-2">
                            <button type="button" class="btn btn-gradient-neutral" @click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-gradient-primary" x-text="replyText ? 'Update' : 'Kirim'"></button>
                        </div>
                    </form>
                </div>
            </div>

        <script>
            function replyModal() {
                return {
                    show: false,
                    commentId: null,
                    replyText: '',
                    openModal(id, reply = '') {
                        this.commentId = id;
                        this.replyText = reply;
                        this.show = true;
                    },
                    closeModal() {
                        this.show = false;
                        this.commentId = null;
                        this.replyText = '';
                    }
                }
            }
        </script>

    </div>


@endsection
