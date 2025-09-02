@extends('layouts.dashboard')
@section('title', 'Pesanan Masuk')

@php
    $title = 'Pesanan Masuk';
    $breadcrumb = [['label' => 'Pesanan'], ['label' => 'Pesanan Masuk']];
@endphp

@section('content')
    @if ($orders->isEmpty())
        <div class="w-full">
            <div
                class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-2.293 2.293a1 1 0 01-.707.293H6a1 1 0 01-.707-.293L3 13m17 0V17a2 2 0 01-2 2H6a2 2 0 01-2-2v-4" />
                </svg>

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-9 4h10m-10 0a2 2 0 110-4h10a2 2 0 110 4m-10 0V20" />
                </svg>
                <p class="text-sm text-gray-500">Belum ada pesanan yang masuk.</p>
            </div>
        </div>
    @else
        <div x-data="{
            selectedOrderId: null,
            orders: @js($orders),
            order: null,
            showModal: false
             }" x-effect="order = orders.find(o => o.id === selectedOrderId) || null"
            class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- KIRI: DAFTAR ORDER -->
            <div class="md:col-span-1">
                <div class="card bg-white border rounded-xl shadow-md">
                    <div class="card-body">
                        <h2 class="text-xl font-bold mb-4">Pelanggan</h2>
                        <ul class="space-y-3">
                            @foreach ($orders as $order)
                                <li @click="selectedOrderId = {{ $order->id }}"
                                    class="border rounded-lg p-3 hover:bg-gray-100 cursor-pointer"
                                    :class="{ 'bg-blue-50 border-blue-400': selectedOrderId === {{ $order->id }} }">
                                    <div class="font-semibold text-gray-800">Reseller {{ $order->reseller->name }}</div>
                                    <div class="text-sm text-gray-500"> <span
                                            class="badge badge-outline {{ $order->status_color }}">{{ $order->status_name }}
                                        </span></div>

                                    <div class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- KANAN: DETAIL ORDER -->
            <div class="md:col-span-2">
                <template x-if="!order">
                    <div class="flex items-center justify-center h-full text-gray-500 border rounded-xl p-6 text-center">
                        <p class="text-sm">Silakan pilih pesanan dari daftar di sebelah kiri untuk melihat detailnya.</p>
                    </div>
                </template>

                <template x-if="order">
                    <div class="card bg-white border rounded-xl shadow-md transition duration-300 ease-in-out transform">
                        <div class="card-body space-y-4">
                            <h2 class="text-xl font-bold text-primary">Detail Order</h2>

                            <div>
                                <p><strong>Reseller:</strong> <span x-text="order.reseller.name"></span></p>
                                <p><strong>Kode Order:</strong> <span x-text="order.order_code"></span></p>
                                <p><strong>Status:</strong> <span :class="['badge', 'badge-outline', order.status_color]"
                                        x-text="order.status_name">
                                    </span>
                                </p>
                                <p><strong>Tanggal Order:</strong>
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</p>
                                <p><strong>Alamat Pengiriman:</strong> <span x-text="order.shipping_address"></span></p>
                                <p><strong>Note:</strong>
                                    <span
                                        x-text="order.note && order.note.trim() !== '' ? order.note : 'Tidak ada note'"></span>
                                </p>
                            </div>

                            <div class="border-t pt-4">
                                <h3 class="text-md font-semibold mb-2">Daftar Produk</h3>
                                <div class="space-y-2">
                                    <template x-for="item in order.order_items" :key="item.id">
                                        <div class="flex justify-between items-center border p-3 rounded-lg">
                                            <div>
                                                <p class="font-medium" x-text="item.product_name"></p>
                                                <p class="text-sm text-gray-500" x-text="'x' + item.quantity"></p>
                                            </div>
                                            <div class="text-sm font-semibold text-right">
                                                Rp<span
                                                    x-text="(item.price_each * item.quantity).toLocaleString('id-ID')"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="border-t pt-4 space-y-2 font-semibold">
                                <!-- Subtotal -->
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>
                                        Rp<span
                                            x-text="order.order_items
                    .reduce((sum, i) => sum + (i.price_each * i.quantity), 0)
                    .toLocaleString('id-ID')"></span>
                                    </span>
                                </div>

                                <!-- Ongkir -->
                                <div class="flex justify-between">
                                    <span>Ongkir</span>
                                    <span>
                                        Rp<span x-text="order.total_shipping.toLocaleString('id-ID')"></span>
                                    </span>
                                </div>

                                <!-- Total -->
                                <div class="border-t pt-2 flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span>
                                        Rp<span
                                            x-text="(
                    order.order_items.reduce((sum, i) => sum + (i.price_each * i.quantity), 0)
                    + order.total_shipping
                    ).toLocaleString('id-ID')"></span>
                                    </span>
                                </div>
                                <!-- Bukti Pembayaran -->
                                <div class="border-t pt-4">
                                    <h3 class="text-md font-semibold mb-2">Metode Pembayaran</h3>
                                    <div class="bg-gray-50 p-3 rounded-lg border text-center">
                                        <span class="font-medium text-gray-700" x-text="order.payment_method"></span>
                                    </div>
                                    <h3 class="text-md font-semibold mb-2">Bukti Pembayaran</h3>

                                    <div
                                        class="bg-gray-50 p-3 rounded-lg border flex flex-col sm:flex-row items-center justify-center gap-4 text-center">
                                        <template x-if="order.payment_proofs">
                                            <img :src="cloudinaryUrl(order.payment_proofs)" alt="Bukti Pembayaran"
                                                class="rounded-lg shadow-md max-w-xs">
                                        </template>

                                        <template x-if="!order.payment_proofs">
                                            <span class="text-gray-500">Belum dikirim bukti pembayaran</span>
                                        </template>
                                    </div>
                                </div>
                            </div>


                            <div class="pt-4 text-right">
                                <button class="btn btn-sm btn-gradient-primary" @click="showModal = true">Ubah Status</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Ubah Status -->
            <div x-show="showModal" style="display: none"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6" @click.away="showModal = false">
                    <h2 class="text-lg font-semibold mb-4">Ubah Status Pesanan</h2>

                    <form method="POST" action="{{ route('order.update-status') }}">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="order_id" :value="selectedOrderId">
                        <label class="block text-sm font-medium mb-2">Pilih Status:</label>
                        <select name="status" class="w-full border rounded p-2 mb-4">
                            <option value="0">Belum di bayar</option>
                            <option value="1">Diproses</option>
                            <option value="2">Dikirim</option>
                            <option value="3">Selesai</option>
                            <option value="4">Dibatalkan</option>
                        </select>

                        <div class="flex justify-end space-x-2">
                            <button type="button" class="btn-gradient-neutral" @click="showModal = false">Batal</button>
                            <button type="submit" class="btn-gradient-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <script>
    function cloudinaryUrl(path) {
        // Laravel yang generate URL dasar
        const base = "{{ rtrim(cloudinary_url('dummy'), 'dummy') }}";
        
        if (!path) {
            return "{{ asset('images/no-image.png') }}"; // fallback kalau kosong
        }
        return base + path;
    }
</script>

@endsection
