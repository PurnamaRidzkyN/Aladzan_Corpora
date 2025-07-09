@extends('layouts.dashboard')
@section('title', 'Riwayat Pesanan')

@section('content')
<section class="w-full lg:px-12 mt-8 space-y-6">

    <!-- Header & Filter -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-primary">Riwayat Pesanan</h2>
        <div class="flex gap-4 items-center">
            <select class="select select-bordered text-sm">
                <option disabled selected>Filter Status</option>
                <option>Selesai</option>
                <option>Dibatalkan</option>
            </select>
            <input type="date" class="input input-bordered text-sm" />
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-xl border border-soft shadow overflow-x-auto">
        <table class="table table-zebra w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ([
                    ['id' => 201, 'customer' => 'Yuki Nishimura', 'date' => '2025-07-07', 'total' => 68000, 'status' => 'Selesai'],
                    ['id' => 202, 'customer' => 'Sato Aiko', 'date' => '2025-07-06', 'total' => 43000, 'status' => 'Dibatalkan'],
                    ['id' => 203, 'customer' => 'Tanaka Ren', 'date' => '2025-07-05', 'total' => 89000, 'status' => 'Selesai']
                ] as $order)
                <tr>
                    <td>#{{ $order['id'] }}</td>
                    <td>{{ $order['customer'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($order['date'])->format('d M Y') }}</td>
                    <td>Rp{{ number_format($order['total'], 0, ',', '.') }}</td>
                    <td>
                        <span class="badge 
                            @if($order['status'] === 'Selesai') badge-success 
                            @else badge-error @endif">
                            {{ $order['status'] }}
                        </span>
                    </td>
                    <td>
                        <label for="modalDetail" class="btn btn-xs btn-outline">Detail</label>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<!-- MODAL: Detail Pesanan -->
<input type="checkbox" id="modalDetail" class="modal-toggle" />
<div class="modal">
    <div class="modal-box w-full max-w-2xl">
        <h3 class="font-bold text-lg mb-2">Detail Pesanan #201</h3>

        <div class="text-sm space-y-1 mb-3">
            <p><strong>Pelanggan:</strong> Yuki Nishimura</p>
            <p><strong>Alamat:</strong> Jl. Harajuku No. 10, Tokyo</p>
            <p><strong>Tanggal:</strong> 07 Juli 2025</p>
            <p><strong>Status:</strong> <span class="badge badge-success">Selesai</span></p>
        </div>

        <div class="mb-2">
            <p class="font-semibold text-sm">Produk:</p>
            <ul class="text-sm list-disc list-inside space-y-1">
                <li>Takoyaki x2 - Rp36.000</li>
                <li>Matcha Latte x1 - Rp15.000</li>
                <li>Onigiri x2 - Rp17.000</li>
            </ul>
        </div>

        <p class="font-semibold text-right">Total: Rp68.000</p>

        <div class="modal-action">
            <label for="modalDetail" class="btn btn-sm">Tutup</label>
        </div>
    </div>
</div>
@endsection
