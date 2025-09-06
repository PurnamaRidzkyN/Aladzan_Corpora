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
        <form class="space-y-3" action="{{ route('checkout.confirm') }}" method="POST" enctype="multipart/form-data">
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
                            <span>
                                {{ ($ongkirPerShop[$shopName] ?? 0) > 0
                                    ? 'Rp' . number_format($ongkirPerShop[$shopName], 0, ',', '.')
                                    : 'Gratis' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pilihan Resi --}}
            <div class="bg-white p-4 rounded-2xl shadow space-y-4">
                <h2 class="font-semibold text-gray-800">Pengaturan Resi</h2>

                {{-- Pilihan Radio --}}
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="has_resi" value="0" class="radio" checked>
                        <span>Resi Otomatis</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="has_resi" value="1" class="radio">
                        <span>Resi Manual</span>
                    </label>
                </div>

                {{-- Form Manual Resi --}}
                <div id="manual-resi-form" class="hidden space-y-3">
                    <div>
                        <label for="resi_number" class="block text-sm font-medium text-gray-700">Nomor Resi</label>
                        <input type="text" id="resi_number" name="resi_number" class="input input-bordered w-full"
                            placeholder="Masukkan nomor resi">
                    </div>

                    <div>
                        <label for="resi_file" class="block text-sm font-medium text-gray-700">Upload File Resi</label>
                        <input type="file" id="resi_file" name="resi_file" class="file-input file-input-bordered w-full">
                    </div>

                    <div>
                        <label for="resi_source_id" class="block text-sm font-medium text-gray-700">Asal Resi</label>
                        <select id="resi_source_id" name="resi_source_id" class="select select-bordered w-full">
                            <option value="">-- Pilih asal resi --</option>
                            @foreach ($resiSources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <script>
                document.querySelectorAll('input[name="has_resi"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const manualForm = document.getElementById('manual-resi-form');
                        if (this.value === '1') {
                            manualForm.classList.remove('hidden');
                        } else {
                            manualForm.classList.add('hidden');
                        }
                    });
                });
            </script>


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
