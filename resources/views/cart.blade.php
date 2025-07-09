@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mx-auto px-4 py-6 pb-32"> <!-- ✅ padding bawah untuk space tombol -->
    <h1 class="text-2xl font-bold text-primary mb-6">Keranjang Belanja</h1>

    @php
        $cartItems = [
            [
                'store' => 'Toko Sakura',
                'items' => [
                    [
                        'id' => 1,
                        'name' => 'Matcha Latte',
                        'price' => 20000,
                        'quantity' => 2,
                        'image' => 'https://via.placeholder.com/100x100?text=Matcha',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Kue Mochi',
                        'price' => 15000,
                        'quantity' => 1,
                        'image' => 'https://via.placeholder.com/100x100?text=Mochi',
                    ],
                ],
            ],
            [
                'store' => 'Toko Fujiwara',
                'items' => [
                    [
                        'id' => 3,
                        'name' => 'Onigiri Tuna',
                        'price' => 10000,
                        'quantity' => 3,
                        'image' => 'https://via.placeholder.com/100x100?text=Onigiri',
                    ],
                ],
            ],[
                  'store' => 'Toko Sakura',
                'items' => [
                    [
                        'id' => 1,
                        'name' => 'Matcha Latte',
                        'price' => 20000,
                        'quantity' => 2,
                        'image' => 'https://via.placeholder.com/100x100?text=Matcha',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Kue Mochi',
                        'price' => 15000,
                        'quantity' => 1,
                        'image' => 'https://via.placeholder.com/100x100?text=Mochi',
                    ],
                ],
            ],
            [
                'store' => 'Toko Fujiwara',
                'items' => [
                    [
                        'id' => 3,
                        'name' => 'Onigiri Tuna',
                        'price' => 10000,
                        'quantity' => 3,
                        'image' => 'https://via.placeholder.com/100x100?text=Onigiri',
                    ],
                ],
            ]
        ];
    @endphp

    <form id="cartForm">
        <div class="space-y-6">
            @foreach ($cartItems as $store)
                <div class="bg-white border shadow rounded-xl p-4">
                    <h2 class="text-lg font-bold text-primary mb-4">{{ $store['store'] }}</h2>
                    <div class="space-y-4">
                        @foreach ($store['items'] as $item)
                        <div class="p-3 border rounded-lg bg-gray-50">
                            <div class="flex justify-between items-center flex-wrap gap-4">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" 
                                        class="item-check checkbox checkbox-primary"
                                        data-price="{{ $item['price'] * $item['quantity'] }}"
                                        data-qty="{{ $item['quantity'] }}">
                                    <img src="{{ $item['image'] }}" class="w-20 h-20 object-cover rounded-lg">
                                    <div class="text-sm sm:text-base">
                                        <p class="font-semibold">{{ $item['name'] }}</p>
                                        <p class="text-gray-600 mb-1">Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" class="btn btn-xs btn-outline" onclick="updateQty(this, -1)">-</button>
                                            <span class="font-medium" data-qty>{{ $item['quantity'] }}</span>
                                            <button type="button" class="btn btn-xs btn-outline" onclick="updateQty(this, 1)">+</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-error ml-auto">Hapus</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ✅ Mobile Checkout Bar -->
        <div class="fixed bottom-16 left-0 right-0 bg-white border-t shadow p-4 z-40 md:hidden">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-lg font-semibold">
                    Total Dipilih: <span id="totalHarga">Rp0</span>
                </div>
                <button type="submit" class="btn btn-primary w-full md:w-auto">Lanjut ke Checkout</button>
            </div>
        </div>
<!-- ✅ Desktop Checkout Bar -->
<div class="hidden md:block fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg p-4 z-50">
    <div class="flex justify-between items-center max-w-5xl mx-auto">
        <div class="text-lg font-semibold">
            Total Dipilih: <span id="totalHargaDesktop">Rp0</span>
        </div>
        <button type="submit" class="btn btn-primary">Lanjut ke Checkout</button>
    </div>
</div>



    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll('.item-check');
        const totalHarga = document.getElementById('totalHarga');
        const totalHargaDesktop = document.getElementById('totalHargaDesktop');

        function updateTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) total += parseInt(cb.dataset.price);
            });
            const formatted = 'Rp' + total.toLocaleString('id-ID');
            if (totalHarga) totalHarga.textContent = formatted;
            if (totalHargaDesktop) totalHargaDesktop.textContent = formatted;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });

        window.updateQty = function (button, delta) {
            const qtyDisplay = button.parentElement.querySelector('[data-qty]');
            let qty = parseInt(qtyDisplay.textContent) + delta;
            if (qty < 1) qty = 1;
            qtyDisplay.textContent = qty;

            const container = button.closest('.p-3');
            const checkbox = container.querySelector('.item-check');
            const pricePerItem = parseInt(checkbox.dataset.price) / parseInt(checkbox.dataset.qty);
            const newTotal = pricePerItem * qty;
            checkbox.dataset.price = newTotal;
            checkbox.dataset.qty = qty;

            if (checkbox.checked) {
                checkbox.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
@endsection
