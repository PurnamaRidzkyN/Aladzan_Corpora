@extends('layouts.dashboard')
@section('title', 'Daftar Pembelian Upgrade Plan')
@php
    $title = 'Daftar Upgrade Plan';
    $breadcrumb = [ ['label' => 'Daftar Upgrade Plan'] ];
@endphp
@section('content')
    <div class="card bg-white shadow-md rounded-xl border border-soft">
        <div class="card-body">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-primary">Daftar Upgrade Plan Reseller (Pending)</h2>
            </div>

            @if ($orders->isEmpty())
                <p class="text-center text-gray-500 py-6">Tidak ada pembelian yang perlu ditinjau.</p>
            @else
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reseller</th>
                                <th>Plan</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                                <th>Metode</th>
                                <th>Bukti</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $index => $order)
                                <tr class="hover:bg-accent-light transition">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-semibold">{{ $order->reseller->name }}</td>
                                    <td>{{ $order->plan->name ?? 'Tidak Diketahui' }}</td>
                                    <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                    <td>{{ $order->discount_code ?? '-' }} (Rp
                                        {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }})</td>
                                    <td>{{ strtoupper($order->payment_method) }}</td>
                                    <td>
                                        <div x-data="{ open: false }" class="inline-block">
                                            @if ($order->payment_proof)
                                                <!-- Thumbnail -->
                                                <img src="{{ cloudinary_url($order->payment_proof) }}"
                                                    alt="Bukti Pembayaran"
                                                    class="w-20 h-20 object-cover rounded-lg border shadow-sm cursor-pointer"
                                                    @click="open = true">

                                                <!-- DaisyUI Modal -->
                                                <input type="checkbox" id="modal-{{ $order->id }}" class="modal-toggle"
                                                    x-model="open">
                                                <label for="modal-{{ $order->id }}" class="modal cursor-pointer">
                                                    <label class="modal-box relative max-w-3xl p-0 cursor-auto" @click.stop>
                                                        <button @click="open = false"
                                                            class="absolute top-2 right-2 btn btn-sm btn-circle btn-error text-white z-50">✕</button>
                                                        <img src="{{ cloudinary_url($order->payment_proof) }}"
                                                            alt="Bukti Pembayaran"
                                                            class="w-full h-auto object-contain rounded-lg">
                                                    </label>
                                                </label>
                                            @else
                                                -
                                            @endif
                                        </div>

                                    </td>
                                    <td class="text-center space-x-1">
                                        <!-- Terima -->
                                        <label for="approve-order-{{ $order->id }}"
                                            class="btn btn-gradient-success btn-xs">Terima</label>
                                        <!-- Tolak -->
                                        <label for="reject-order-{{ $order->id }}"
                                            class="btn btn-gradient-error btn-xs">Tolak</label>

                                        <!-- Modal Terima -->
                                        <input type="checkbox" id="approve-order-{{ $order->id }}"
                                            class="modal-toggle" />
                                        <div class="modal" role="dialog">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Konfirmasi Terima</h3>
                                                <p class="py-4">Yakin ingin **menyetujui** pembelian paket
                                                    <strong>{{ $order->plan->name }}</strong> oleh
                                                    {{ $order->reseller->name }}?
                                                </p>
                                                <div class="modal-action">
                                                    <form action="{{ route('admin.orders.approve', $order->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-gradient-success">Ya,
                                                            Terima</button>
                                                    </form>
                                                    <label for="approve-order-{{ $order->id }}"
                                                        class="btn btn-gradient-neutral">Batal</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Tolak -->
                                        <input type="checkbox" id="reject-order-{{ $order->id }}"
                                            class="modal-toggle" />
                                        <div class="modal" role="dialog">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Konfirmasi Tolak</h3>
                                                <p class="py-4">Yakin ingin **menolak** pembelian paket
                                                    <strong>{{ $order->plan->name }}</strong> oleh
                                                    {{ $order->reseller->name }}?
                                                </p>
                                                <div class="modal-action">
                                                    <form action="{{ route('admin.orders.reject', $order->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-gradient-error">Ya,
                                                            Tolak</button>
                                                    </form>
                                                    <label for="reject-order-{{ $order->id }}"
                                                        class="btn btn-gradient-neutral">Batal</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
@endsection
