@extends('layouts.app')
@section('title', 'Detail Pembayaran Paket')

@section('content')
<div class="flex items-center justify-center px-4 md:px-0 mt-6">
    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 max-w-3xl w-full border border-gray-200">
        <h1 class="text-2xl md:text-3xl font-bold text-center text-blue-700 mb-6">Detail Pembayaran Paket</h1>

        @if($order)
        <div class="space-y-6">

            <!-- Kartu info paket -->
            <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">{{ $order->plan->name ?? 'Tidak Diketahui' }}</h2>
                    <span class="text-gray-500 text-sm">{{ $order->created_at->format('d-m-Y H:i') }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-2 text-gray-700 text-sm">
                    <div>
                        <p><strong>Harga:</strong></p>
                        <p class="font-medium">Rp {{ number_format($order->price,0,',','.') }}</p>
                    </div>
                    <div>
                        <p><strong>Diskon:</strong></p>
                        <p class="font-medium">{{ $order->discount_code ?? '-' }} (Rp {{ number_format($order->discount_amount ?? 0,0,',','.') }})</p>
                    </div>
                    <div>
                        <p><strong>Metode:</strong></p>
                        <p class="font-medium">{{ strtoupper($order->payment_method) }}</p>
                    </div>
                    <div>
                        <p><strong>Status:</strong></p>
                        @if($order->status === 0)
                            <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold text-xs">Menunggu Tinjauan</span>
                        @elseif($order->status === 1)
                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 font-semibold text-xs">Lunas</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 font-semibold text-xs">{{ ucfirst($order->status) }}</span>
                        @endif
                    </div>
                </div>

                @if($order->payment_proof)
                <div class="mt-4">
                    <p class="font-medium mb-2">Bukti Pembayaran:</p>
                    <img src="{{ cloudinary_url($order->payment_proof) }}" alt="Bukti Pembayaran" class="w-full max-w-md rounded-lg border shadow-sm">
                </div>
                @endif
            </div>

        </div>
        @else
        <p class="text-center text-gray-500">Anda belum melakukan upgrade paket.</p>
        @endif

      <div class="text-center mt-6 flex justify-center gap-4">
    <!-- Kembali ke Beranda -->
    <a href="/"
       class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium">
        Beranda
    </a>

    <!-- Kembali ke Halaman Sebelumnya -->
    <button onclick="history.back()"
        class="px-4 py-2 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50 text-sm font-medium">
        Kembali
    </button>
</div>

    </div>
</div>
@endsection
