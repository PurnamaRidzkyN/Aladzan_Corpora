@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto p-6 space-y-8">
        <!-- Header Order -->
        <div class="bg-white shadow rounded-2xl p-6 border">
            <div class="flex flex-col md:flex-row justify-between md:items-center mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_code }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Tanggal Pesan: <span class="font-medium">{{ $order->created_at->format('d M Y') }}</span>
                    </p>
                    <p class="text-sm text-gray-500">
                        Metode Pembayaran: <span class="font-medium">{{ ucfirst($order->payment_method) }}</span>
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="badge badge-lg badge-outline badge-{{ $order->status_color }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status_name)) }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 text-sm text-gray-600 border-t pt-4">
                <p>Total Harga: <span class="font-semibold text-gray-800">Rp
                        {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                <p>Ongkir: <span class="font-semibold text-gray-800">Rp
                        {{ number_format($order->total_shipping, 0, ',', '.') }}</span></p>
            </div>
            
        </div>
@if ($order->payment_proofs)
    <div class="bg-white shadow rounded-2xl p-6 border">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            Bukti Pembayaran:
    </h3>
        <div class="w-full max-w-xs">
            <img src="{{ cloudinary_url($order->payment_proofs,'image','w_500,q_auto') }}"
                 alt="Bukti Pembayaran"
                 class="w-full rounded-xl border shadow-sm hover:shadow-md transition">
        </div>
    </div>
@endif

        <!-- Timeline Status -->
        <div class="bg-white shadow rounded-2xl p-6 border">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pesanan</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $statuses = [
                        'is_paid_at' => 'Dibayar',
                        'is_processed_at' => 'Diproses',
                        'is_shipped_at' => 'Dikirim',
                        'is_done_at' => 'Selesai',
                    ];
                @endphp

                @foreach ($statuses as $field => $label)
                    <div class="flex flex-col items-center">
                        @if ($order->$field)
                            <div class="w-10 h-10 flex items-center justify-center bg-green-500 text-white rounded-full">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-700">{{ $label }}</p>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($order->$field)->format('d M Y H:i') }}</p>
                        @else
                            <div class="w-10 h-10 flex items-center justify-center bg-gray-300 text-white rounded-full">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-500">{{ $label }}</p>
                            <p class="text-xs text-gray-400">Menunggu...</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Loop Order Items -->
        @php
            $shops = $order->orderItems->groupBy(fn($item) => $item->variant->product->shop->id);
        @endphp

        @foreach ($shops as $shopId => $items)
            @php $shop = $items->first()->variant->product->shop; @endphp

            <!-- Card per toko -->
            <div class="bg-white shadow rounded-2xl border overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b flex items-center gap-2">
                    <i class="fa-solid fa-store text-blue-500"></i>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $shop->name }}</h3>
                </div>
                <div class="divide-y">
                    @foreach ($items as $item)
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 p-4">
                            <!-- Gambar -->
                            <div class="w-28 h-28 flex-shrink-0 overflow-hidden rounded-xl border">
                                <img src="{{ cloudinary_url($item->variant->media->file_path ?? 'productDefault_mpgglw', 'image', 'w_400,h_400,c_fill') }}"
                                    alt="{{ $item->variant->name }}" class="w-full h-full object-cover">
                            </div>
                            <!-- Detail -->
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $item->variant->product->name }}</h4>
                                <p class="text-sm text-gray-600">Varian: {{ $item->variant->name }}</p>
                                <p class="text-sm text-gray-600">Qty: <span
                                        class="font-medium">{{ $item->quantity }}</span></p>
                            </div>
                            <!-- Harga -->
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Subtotal</p>
                                <p class="text-lg font-bold text-gray-800">
                                    Rp {{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @if ($review)
        <div class="bg-white p-4 rounded-xl shadow-sm border">
            {{-- Header review --}}
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-sm font-bold text-blue-800">
                        {{ strtoupper(substr($review->reseller->name ?? 'AN', 0, 2)) }}
                    </div>
                    <span class="font-semibold text-gray-800 text-sm">
                        {{ $review->reseller->name ?? 'Anonim' }}
                    </span>
                </div>
                <div class="text-yellow-400 text-sm">
                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                </div>
            </div>

            {{-- Isi review --}}
            <p class="text-gray-700 text-sm leading-relaxed">{{ $review->comment }}</p>
<p class="text-xs text-gray-400">
                                {{ $review->reseller->name ?? 'user' }} • {{ $review->created_at->format('d M Y') }}
                            </p>
            {{-- Balasan admin kalau ada --}}
            @if (!empty($review->reply))
                <div class="mt-3 pl-4 border-l-4 border-blue-300 bg-blue-50 p-3 rounded-md">
                    <div class="flex items-center gap-2 mb-1">
                        
                        <span class="font-semibold text-blue-800 text-xs">Balasan Admin {{ $review->admin->name }}</span>
                    </div>
                    <p class="text-gray-700 text-sm">{{ $review->reply }}</p>
                    @if ($review->reply_at)
                        <span class="text-gray-500 text-xs">
                            Dibalas pada {{ \Carbon\Carbon::parse($review->reply_at)->format('d M Y H:i') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    @else
        <p class="text-gray-500 italic">Anda belum melakukan ulasan untuk pesanan ini.</p>
    @endif
    </div>


@endsection
