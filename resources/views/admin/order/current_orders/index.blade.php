@extends('layouts.dashboard')
@section('title', 'Order Masuk')

@section('content')
@php
    $orders = [
        [
            'buyer' => 'Ayu Kusuma',
            'status' => 'Menunggu Pembayaran',
            'created_at' => '2024-07-30',
            'address' => 'Jl. Mawar No. 12, Malang',
            'items' => [
                ['store' => 'Toko Sakura', 'name' => 'Matcha Latte', 'qty' => 2, 'price' => 20000],
                ['store' => 'Toko Sakura', 'name' => 'Kue Mochi', 'qty' => 1, 'price' => 15000],
                ['store' => 'Toko Fujiwara', 'name' => 'Onigiri Tuna', 'qty' => 3, 'price' => 10000],
            ],
        ],
        [
            'buyer' => 'Budi Santoso',
            'status' => 'Sedang Dikirim',
            'created_at' => '2024-07-29',
            'address' => 'Jl. Kenanga No. 5, Surabaya',
            'items' => [
                ['store' => 'Toko Fujiwara', 'name' => 'Onigiri Salmon', 'qty' => 1, 'price' => 12000],
            ],
        ],
    ];

    $selectedOrder = $orders[0];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Kiri: Daftar Order -->
    <div class="md:col-span-1">
        <div class="card bg-white border rounded-xl shadow-md">
            <div class="card-body">
                <h2 class="text-xl font-bold mb-4">Pelanggan</h2>
                <ul class="space-y-3">
                    @foreach($orders as $order)
                    <li class="border rounded-lg p-3 hover:bg-gray-100 cursor-pointer {{ $loop->first ? 'bg-blue-50 border-blue-400' : '' }}">
                        <div class="font-semibold text-gray-800">{{ $order['buyer'] }}</div>
                        <div class="text-sm text-gray-500">{{ $order['status'] }}</div>
                        <div class="text-xs text-gray-400">{{ $order['created_at'] }}</div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Kanan: Detail Order -->
    <div class="md:col-span-2">
        <div class="card bg-white border rounded-xl shadow-md">
            <div class="card-body space-y-4">
                <h2 class="text-xl font-bold text-primary">Detail Order</h2>

                <div>
                    <p><strong>Nama:</strong> {{ $selectedOrder['buyer'] }}</p>
                    <p><strong>Status:</strong> <span class="badge badge-warning">{{ $selectedOrder['status'] }}</span></p>
                    <p><strong>Tanggal Order:</strong> {{ $selectedOrder['created_at'] }}</p>
                    <p><strong>Alamat Pengiriman:</strong> {{ $selectedOrder['address'] }}</p>
                </div>

                <div class="border-t pt-4">
                    <h3 class="text-md font-semibold mb-2">Daftar Produk</h3>
                    <div class="space-y-2">
                        @foreach($selectedOrder['items'] as $item)
                        <div class="flex justify-between items-center border p-3 rounded-lg">
                            <div>
                                <p class="font-medium">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-500">{{ $item['store'] }} - x{{ $item['qty'] }}</p>
                            </div>
                            <div class="text-sm font-semibold text-right">
                                Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t pt-4 flex justify-between font-semibold">
                    <span>Total</span>
                    <span>
                        @php
                            $total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $selectedOrder['items']));
                        @endphp
                        Rp{{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>

                <div class="pt-4 text-right">
                    <button class="btn btn-sm btn-primary">Ubah Status</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
