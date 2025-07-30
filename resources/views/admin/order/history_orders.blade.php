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
                <input type="text" placeholder="Cari berdasarkan nama pembeli atau kode pesanan" id="searchInput" class="input input-bordered w-full" />
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
                                    <span class="badge badge-outline badge-{{ $order->status_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <label for="order-detail-{{ $order->id }}" class="btn btn-info btn-xs text-white">Lihat Detail</label>

                                    <!-- Modal Detail -->
                                    <input type="checkbox" id="order-detail-{{ $order->id }}" class="modal-toggle" />
                                    <div class="modal" role="dialog">
                                        <div class="modal-box w-full max-w-lg">
                                            <h3 class="font-bold text-lg mb-4">Detail Pesanan</h3>
                                            <ul class="space-y-2 text-sm">
                                                <li><strong>Nama Pembeli:</strong> {{ $order->reseller->name }}</li>
                                                <li><strong>Kode Pesanan:</strong> {{ $order->order_code }}</li>
                                                <li><strong>Total:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</li>
                                                <li><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</li>
                                                <li><strong>Dibuat pada:</strong> {{ $order->created_at->format('d M Y H:i') }}</li>
                                            </ul>
                                            <div class="modal-action">
                                                <label for="order-detail-{{ $order->id }}" class="btn btn-gradient-neutral">Tutup</label>
                                            </div>
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
