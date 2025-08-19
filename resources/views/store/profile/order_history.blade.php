@extends('layouts.app')

@section('content')
    <div x-data="{ open: false, orderId: null, orderCode: null }">
        <div class="max-w-7xl mx-auto px-1 py-1">
            <h1 class="text-2xl font-bold text-primary mb-4">Riwayat Pembelian</h1>

            <!-- Baris tombol aksi -->
            <div class="flex justify-between items-center mb-6">
                <!-- Tombol kembali -->
                <a href="{{ route('profile') }}" class="btn btn-gradient-neutral">
                    ← Kembali
                </a>
            </div>
            <!-- Tab Navigation -->
            <div class="flex justify-between border-b border-sky-300 text-sm font-medium text-gray-600 overflow-x-auto">
                <button class="tab-button px-4 py-2 border-b-2 border-sky-500 text-sky-700 whitespace-nowrap"
                    onclick="showTab('belum')">Belum Dibayar</button>
                <button class="tab-button px-4 py-2 hover:text-sky-700 whitespace-nowrap"
                    onclick="showTab('proses')">Diproses</button>
                <button class="tab-button px-4 py-2 hover:text-sky-700 whitespace-nowrap"
                    onclick="showTab('kirim')">Dikirim</button>
                <button class="tab-button px-4 py-2 hover:text-sky-700 whitespace-nowrap"
                    onclick="showTab('selesai')">Selesai</button>
                <button class="tab-button px-4 py-2 hover:text-sky-700 whitespace-nowrap"
                    onclick="showTab('cancel')">Dibatalkan</button>
            </div>

            <!-- Tab Content -->
            @php
                $statusLabels = [
                    0 => ['Belum Dibayar', 'bg-yellow-100 text-yellow-700'],
                    1 => ['Diproses', 'bg-blue-100 text-blue-700'],
                    2 => ['Dikirim', 'bg-purple-100 text-purple-700'],
                    3 => ['Selesai', 'bg-green-100 text-green-700'],
                    4 => ['Dibatalkan', 'bg-red-100 text-red-700'],
                ];
            @endphp

            @foreach ([0 => 'belum', 1 => 'proses', 2 => 'kirim', 3 => 'selesai', 4 => 'cancel'] as $status => $id)
                <div id="{{ $id }}" class="tab-content {{ $status === 0 ? '' : 'hidden' }} mt-6 grid gap-4">
                    @php $filtered = $orders->where('status', $status); @endphp

                    @forelse ($filtered as $order)
                        <div class="bg-white shadow-md rounded-xl p-4">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-semibold text-gray-700">#{{ $order->order_code }}</h3>
                                <span class="text-xs px-2 py-1 rounded {{ $statusLabels[$order->status][1] }}">
                                    {{ $statusLabels[$order->status][0] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mb-1">Tanggal: {{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-sm text-gray-500 mb-3">Total: Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</p>

                            <div class="text-right space-x-2">
                                <a href="{{ route('order.detail', $order->order_code) }}" class="btn-gradient-neutral">Lihat
                                    Detail</a>
                                @if ($order->status === 0)
                                    <a href="{{ route('payment', $order->order_code) }}" class="btn-gradient-primary">Bayar
                                        Sekarang</a>
                                    <button x-data="{ id: {{ $order->id }}, code: '{{ $order->order_code }}' }" @click="open = true; orderId = id; orderCode = code"
                                        class="btn btn-gradient-error">
                                        Batalkan
                                    </button>
                                @elseif ($order->status === 3 && $order->rating->isEmpty())
                                    @php
                                        $orderId = $order->id;
                                        $productId = optional($order->orderItems->first()->variant->product)->id;
                                    @endphp
                                    <label class="btn-gradient-primary"
                                        @click="$dispatch('open-review-modal', { productId: {{ $productId }}, orderId: {{ $orderId }} })">
                                        Berikan rating
                                    </label>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="w-full">
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
                                <p class="text-sm text-gray-500">Tidak ada pesanan yang sesuai dengan status ini.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            @endforeach
        </div>
        <template x-if="open">
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Pembatalan</h2>
                    <p class="text-gray-600 mb-6">
                        Yakin ingin membatalkan pesanan <span class="font-bold">#<span x-text="orderCode"></span></span>?
                    </p>

                    <div class="flex justify-end gap-3">
                        <button @click="open = false"
                            class="btn-gradient-neutral">
                            Kembali
                        </button>

                        <form action={{ route('order.cancel') }} method="POST">
                            @csrf
                            <input type="hidden" name="order_id" x-model="orderId">
                            <button type="submit" class="btn-gradient-error">
                                Ya, Batalkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <!-- Modal -->
    <dialog id="review_modal" class="modal" x-data="{ rating: 0, productId: null, orderId: null }"
        @open-review-modal.window="
            productId = $event.detail.productId;
            orderId = $event.detail.orderId;
            rating = 0;
            $el.showModal()
        ">

        <div class="modal-box w-full max-w-md">
            <h3 class="font-bold text-lg mb-4">Beri Penilaian</h3>

            <!-- Bintang -->
            <div class="flex justify-center mb-4">
                <template x-for="star in 5" :key="star">
                    <svg @click="rating = star" :class="rating >= star ? 'text-yellow-400' : 'text-gray-300'"
                        xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 cursor-pointer transition" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.118 3.446a1 1 0 00.95.69h3.631c.969 0 1.371 1.24.588 1.81l-2.938 2.135a1 1 0 00-.364 1.118l1.118 3.446c.3.921-.755 1.688-1.54 1.118L10 13.347l-2.938 2.135c-.784.57-1.838-.197-1.539-1.118l1.117-3.446a1 1 0 00-.364-1.118L3.338 8.873c-.784-.57-.38-1.81.588-1.81h3.631a1 1 0 00.95-.69l1.118-3.446z" />
                    </svg>
                </template>
            </div>

            <!-- Form ulasan -->
            <form method="POST" action="{{ route('review') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" :value="productId">
                <input type="hidden" name="order_id" :value="orderId">
                <input type="hidden" name="rating" :value="rating">

                <textarea name="comment" class="textarea textarea-bordered w-full" rows="3" placeholder="Tulis ulasan kamu..."></textarea>

                <div class="modal-action">
    <form method="dialog">
        <button type="button" class="btn-gradient-neutral" 
            @click="$root.closest('dialog').close()">
            Batal
        </button>
    </form>
    <button type="submit" class="btn-gradient-primary">Kirim</button>
</div>

            </form>
        </div>
    </dialog>


    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');

            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('border-b-2', 'border-sky-500', 'text-sky-700');
            });
            event.target.classList.add('border-b-2', 'border-sky-500', 'text-sky-700');
        }
    </script>
@endsection
