@extends('layouts.dashboard')
@section('title', 'Pesanan Masuk')

@php
    $title = 'Pesanan Masuk';
    $breadcrumb = [['label' => 'Pesanan'], ['label' => 'Pesanan Masuk']];
@endphp

@section('content')
    <div
        x-data="{
            selectedOrderId: null,
            orders: @js($orders),
            order: null,
            showModal: false
        }"
        x-effect="order = orders.find(o => o.id === selectedOrderId) || null"
        class="grid grid-cols-1 md:grid-cols-3 gap-6"
    >
        <!-- KIRI: DAFTAR ORDER -->
        <div class="md:col-span-1">
            <div class="card bg-white border rounded-xl shadow-md">
                <div class="card-body">
                    <h2 class="text-xl font-bold mb-4">Pelanggan</h2>
                    <ul class="space-y-3">
                        @forelse ($orders as $order)
                            <li
                                @click="selectedOrderId = {{ $order->id }}"
                                class="border rounded-lg p-3 hover:bg-gray-100 cursor-pointer"
                                :class="{ 'bg-blue-50 border-blue-400': selectedOrderId === {{ $order->id }} }"
                            >
                                <div class="font-semibold text-gray-800">Reseller {{ $order->reseller->name }}</div>
                                <div class="text-sm text-gray-500"> <span class="badge badge-outline badge-{{ $order->status_color }}">{{ $order->status_name }} </span></div>
                                <div class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</div>
                            </li>
                        @empty
                            <li class="text-center text-gray-500 text-sm py-6">
                                Belum ada pesanan yang masuk.
                            </li>
                        @endforelse
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
                            <p><strong>Status:</strong> <span class="badge badge-outline badge-{{ $order->status_color }}" x-text="order.status_name"></span></p>
                            <p><strong>Tanggal Order:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</p>
                            <p><strong>Alamat Pengiriman:</strong> <span x-text="order.shipping_address"></span></p>
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
                                            Rp<span x-text="(item.price_each * item.quantity).toLocaleString('id-ID')"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="border-t pt-4 flex justify-between font-semibold">
                            <span>Total</span>
                            <span>
                                Rp<span x-text="order.order_items.reduce((sum, i) => sum + (i.price_each * i.quantity), 0).toLocaleString('id-ID')"></span>
                            </span>
                        </div>

                        <div class="pt-4 text-right">
                            <button class="btn btn-sm btn-primary" @click="showModal = true">Ubah Status</button>
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
                        <button type="button" class="btn btn-sm" @click="showModal = false">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
