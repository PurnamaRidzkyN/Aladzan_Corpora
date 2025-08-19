@extends('layouts.app')

@section('content')
    <div class="mx-auto p-2 space-y-3">
        {{-- Alamat Pengiriman --}}
        <div class="bg-white p-4 rounded-2xl shadow">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Alamat Pengiriman</h2>

            @if ($address)
                <div class="border rounded-xl p-4 bg-gray-50">
                    <p class="font-semibold text-gray-800">{{ $address->recipient_name }}</p>
                    <p class="text-sm text-gray-600">
                        {{ $address->address_detail }},
                        {{ $address->sub_district }},
                        {{ $address->district }},
                        {{ $address->city }},
                        {{ $address->province }},
                        {{ $address->postal_code }}
                    </p>
                    <p class="text-sm text-gray-600">{{ $address->phone_number }}</p>
                </div>
                <input type="hidden" name="address_id" value="{{ $address->id }}">
            @else
                <div class="border rounded-xl p-4 bg-gray-50">
                    <p>Belum ada alamat. Silakan buat alamat terlebih dahulu.</p>
                </div>
            @endif
        </div>

        {{-- Form Checkout --}}
        <form class="space-y-3" action="{{ route('checkout.confirm') }}" method="POST">
            @csrf
            @method('POST')

            {{-- Daftar Produk Per Toko --}}
            <div class="space-y-3">
                @foreach ($cartItems as $shopName => $items)
                    <div class="bg-white p-4 rounded-2xl shadow space-y-4">
                        <h2 class="text-lg font-bold text-blue-700">{{ $shopName }}</h2>
                        <div class="space-y-3">
                            @foreach ($items as $item)
                                @php
                                    $qty = $item->quantity;
                                    $price = $item->variant->price;
                                    $subtotal = $price * $qty;
                                @endphp

                                <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">

                                <div class="flex items-center gap-4 border-b pb-3">
                                    <img src="{{ $item->variant->media?->file_path ? cloudinary_url($item->variant->media->file_path) : 'https://placehold.co/80x80' }}"
                                        class="w-20 h-20 rounded-lg object-cover border" />

                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800">{{ $item->variant->product->name }}</p>
                                        <p class="text-sm text-gray-500">Varian: {{ $item->variant->name }}</p>
                                        <p class="text-sm text-gray-600">Jumlah: {{ $qty }}</p>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-semibold text-gray-800">
                                            Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-between text-sm text-gray-600 mt-2">
                            <span>Total Barang</span>
                            <span>Rp{{ number_format($shopSubtotals[$shopName], 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Ongkir</span>
                            <span>Rp{{ number_format($ongkirPerShop[$shopName] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Catatan Pembeli --}}
            <div class="bg-white p-4 rounded-2xl shadow space-y-2">
                <label for="note" class="block font-medium text-sm text-gray-700">Catatan untuk Penjual</label>
                <textarea id="note" name="note" rows="3" class="textarea textarea-bordered w-full"
                    placeholder="Contoh: Tolong bungkus dengan rapi ya..."></textarea>
            </div>


            {{-- Ringkasan Biaya --}}
            <div class="rounded-2xl bg-white p-4 shadow space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($subtotalAll, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Total Ongkir</span>
                    <span>Rp{{ number_format($totalOngkir, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-semibold text-base border-t pt-2">
                    <span>Total</span>
                    <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <input type="hidden" name="total_price" value="{{ $total }}">
            <input type="hidden" name="total_shipping" value="{{ $totalOngkir }}">
            <input type="hidden" name="address_id" value="{{ $address?->id }}">

         <div class="mt-6">
    <button type="submit" class="btn btn-primary w-full py-3 text-lg rounded-xl shadow-lg">
         <i class="fas fa-wallet mr-2"></i> Bayar Sekarang
    </button>
</div>

        </form>
    </div>
@endsection
