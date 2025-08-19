@extends('layouts.dashboard')
@section('title', 'Riwayat Pesanan')

@php
    $title = 'Riwayat Pesanan';
    $breadcrumb = [['label' => 'Pesanan'], ['label' => 'Riwayat Pesanan']];
@endphp

@section('content')
    <section class="w-full lg:px-12 mt-8">
        <div class="card bg-white shadow-md rounded-xl border border-soft">
            <div class="card-body">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-primary">Riwayat Pesanan</h2>
                </div>

                <!-- Search bar -->
                <div class="form-control w-full mb-4">
                    <input type="text" placeholder="Cari berdasarkan nama pembeli atau kode pesanan" id="searchInput"
                        class="input input-bordered w-full" />
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm" id="orderTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Pembeli</th>
                                <th>Kode Pesanan</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="orderTableBody">
                            @forelse ($orders as $index => $order)
                                <tr class="hover:bg-accent-light transition">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="order-name">{{ $order->reseller->name }}</td>
                                    <td class="order-code">{{ $order->order_code }}</td>
                                    <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-outline {{ $order->status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status_name)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <label for="order-detail-{{ $order->id }}"
                                            class="btn btn-info btn-xs text-white">Lihat Detail</label>

                                        <!-- Modal Detail -->
                                        <input type="checkbox" id="order-detail-{{ $order->id }}" class="modal-toggle" />
                                        <div class="modal" role="dialog" id="order-detail-{{ $order->id }}">
                                            <div class="modal-box w-full max-w-3xl"> <!-- Header -->
                                                <h2
                                                    class="text-xl font-bold text-primary flex items-center gap-2 border-b pb-3">
                                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Detail Order
                                                </h2>
                                                <div class="space-y-6">
                                                    <!-- Info Utama -->
                                                    <section
                                                        class="bg-gray-50 p-4 rounded-lg border text-sm grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6 text-left">
                                                        <p><strong>Reseller:</strong> {{ $order->reseller->name }}</p>
                                                        <p><strong>Kode Order:</strong> {{ $order->order_code }}</p>
                                                        <p><strong>Status:</strong>
                                                            <span
                                                                class="badge {{ $order->status_color }} text-white">{{ $order->status_name }}</span>
                                                        </p>
                                                        <p><strong>Tanggal Order:</strong>
                                                            {{ $order->created_at->format('d M Y') }}</p>
                                                        <p class="sm:col-span-2"><strong>Alamat Pengiriman:</strong>
                                                            {{ $order->shipping_address }}</p>
                                                        <p class="sm:col-span-2"><strong>Note:</strong>
                                                            {{ $order->note ?: 'Tidak ada note' }}</p>
                                                    </section>

                                                    <!-- Daftar Produk -->
                                                    <section>
                                                        <h3 class="text-md font-semibold mb-3">Daftar Produk</h3>
                                                        <div class="space-y-2">
                                                            @foreach ($order->orderItems as $item)
                                                                <div
                                                                    class="flex justify-between items-center border p-3 rounded-lg bg-white shadow-sm">
                                                                    <div>
                                                                        <p class="font-medium">{{ $item->product_name }}
                                                                        </p>
                                                                        <p class="text-sm text-gray-500">
                                                                            x{{ $item->quantity }}</p>
                                                                    </div>
                                                                    <div class="text-sm font-semibold text-primary">
                                                                        Rp{{ number_format($item->price_each * $item->quantity, 0, ',', '.') }}
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </section>
                                                    <!-- Subtotal, Ongkir, Total -->
                                                    <section class="space-y-2 font-semibold">
                                                        <div class="flex justify-between">
                                                            <span>Subtotal</span>
                                                            <span>Rp{{ number_format($order->orderItems->sum(fn($i) => $i->price_each * $i->quantity), 0, ',', '.') }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span>Ongkir</span>
                                                            <span>Rp{{ number_format($order->total_shipping, 0, ',', '.') }}</span>
                                                        </div>
                                                        <div
                                                            class="border-t pt-2 flex justify-between text-lg font-bold text-primary">
                                                            <span>Total</span>
                                                            <span>
                                                                Rp{{ number_format($order->orderItems->sum(fn($i) => $i->price_each * $i->quantity) + $order->total_shipping, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    </section>
                                                    <!-- Pembayaran -->
                                                    <section>
                                                        <h3 class="text-md font-semibold mb-2">Metode Pembayaran</h3>
                                                        <div class="bg-white p-3 rounded-lg border shadow-sm text-center">
                                                            <span class="font-medium text-gray-700">
                                                                {{ $order->payment_method ?? 'Belum memilih metode pembayaran' }}
                                                            </span>
                                                        </div>

                                                        <h3 class="text-md font-semibold mb-2 mt-4">Bukti Pembayaran</h3>
                                                        @if ($order->payment_proofs)
                                                            <div
                                                                class="bg-white p-3 rounded-lg border shadow-sm flex flex-col sm:flex-row items-center justify-center gap-4 text-center">
                                                                <img src="{{ cloudinary_url($order->payment_proofs) }}"
                                                                    alt="Bukti Pembayaran"
                                                                    class="rounded-lg shadow-md max-w-xs">
                                                            </div>
                                                        @else
                                                            <div
                                                                class="bg-gray-50 p-6 rounded-lg border shadow-sm text-center text-gray-500 italic">
                                                                Belum ada bukti pembayaran
                                                            </div>
                                                        @endif
                                                    </section>



                                                </div> <!-- Footer -->
                                                <div class="modal-action mt-6"> <label
                                                        for="order-detail-{{ $order->id }}"
                                                        class="btn btn-gradient-neutral">Tutup</label> </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">Belum ada pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Script Pencarian -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('searchInput');
            const rows = document.querySelectorAll('#orderTableBody tr');

            input.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();
                rows.forEach(row => {
                    const name = row.querySelector('.order-name')?.textContent.toLowerCase() || '';
                    const code = row.querySelector('.order-code')?.textContent.toLowerCase() || '';

                    if (name.includes(keyword) || code.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
