@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')
    <div class=" mx-auto px-4 md:px-6 py-6 pb-32"x-data="{ deleteId: null }">
        <h1 class="text-2xl font-bold text-primary mb-6">Keranjang Belanja</h1>
{{ $errors->first('resi_file') }}

        <form id="cartForm" method="POST" action="{{ route('checkout.chooseAddress') }}">
            @csrf
            <input type="hidden" name="items_json" id="itemsJson">

            <div class="space-y-6 pb-40"> {{-- Tambahan padding bawah agar tidak nempel checkout bar --}}
                @forelse ($cartItems as $storeName => $items)
                    <div class="bg-white border shadow rounded-xl p-4">
                        <h2 class="text-lg font-bold text-primary mb-4">{{ $storeName }}</h2>
                        <div class="space-y-4">
                            @foreach ($items as $item)
                                <div class="p-3 border rounded-lg bg-gray-50">
                                    <div class="flex justify-between items-center flex-wrap gap-4">
                                        <div class="flex items-center gap-4">
                                            <input type="checkbox" class="item-check checkbox checkbox-primary"
                                                name="selected_items[]" value="{{ $item->id }}"
                                                data-price="{{ $item->variant->price * $item->quantity }}"
                                                data-qty="{{ $item->quantity }}">
                                            <img src="{{ $item->variant->media?->file_path ? cloudinary_url($item->variant->media->file_path, 'image', 'w_80,h_80,c_fill,q_auto,f_auto') : cloudinary_url('productDefault_mpgglw', 'image', 'w_80,h_80,c_fill,q_auto,f_auto')  }}"
                                                alt="Gambar Produk" class="w-20 h-20 object-cover rounded-lg" />

                                            <div class="text-sm sm:text-base">
                                                <a href="{{ route('product.show', $item->variant->product->slug) }}"
                                                    class="no-underline hover:underline">
                                                    {{ $item->variant->product->name }}
                                                </a>
                                                <p class="text-sm text-gray-600">{{ $item->variant->name }}</p>
                                                <p class="text-gray-600 mb-1">
                                                    Rp{{ number_format($item->variant->price, 0, ',', '.') }}</p>
                                                <div class="flex items-center space-x-2">
                                                    <button type="button" class="btn btn-xs btn-outline"
                                                        onclick="updateQty(this, -1)">-</button>
                                                    <span class="font-medium" data-qty>{{ $item->quantity }}</span>
                                                    <button type="button" class="btn btn-xs btn-outline"
                                                        onclick="updateQty(this, 1)">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        <button  type="button"  class="btn btn-gradient-error btn-xs"
                                            @click="deleteId = {{ $item->id }}; cart_modal.showModal()">
                                            Hapus
                                        </button>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="w-full">
                        <div
                            class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-200 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M6 6h15l-1.5 9h-13zM6 6l-1.5-3M10 21a1 1 0 100-2 1 1 0 000 2zM18 21a1 1 0 100-2 1 1 0 000 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 text-center">Keranjang kamu masih kosong. Silakan tambahkan
                                produk terlebih dahulu.</p>
                        </div>
                    </div>
                @endforelse
            </div>


            <!-- ✅ Mobile Checkout Bar -->
            <div class="fixed bottom-16 left-0 right-0 bg-white border-t shadow-md p-4 z-40 md:hidden">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-lg font-semibold text-gray-700">
                        Total Dipilih: <span id="totalHarga" class="text-primary font-bold">Rp0</span>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary w-full md:w-auto">Lanjut ke Checkout</button>
                </div>
            </div>

            <!-- ✅ Desktop Checkout Bar -->
            <div class="hidden md:block fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg p-4 z-50">
                <div class="flex justify-between items-center max-w-5xl mx-auto">
                    <div class="text-lg font-semibold text-gray-700">
                        Total Dipilih: <span id="totalHargaDesktop" class="text-primary font-bold">Rp0</span>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary px-6 py-2 text-base">Lanjut ke Checkout</button>
                </div>
            </div>
        </form>
        <dialog id="cart_modal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Konfirmasi</h3>
                <p class="py-4">Yakin ingin menghapus item ini?</p>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">Batal</button>
                    </form>
                    <!-- Form untuk delete -->
                    <form method="POST" :action="`/cart/${deleteId}`" @submit.prevent="$event.target.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error">Hapus</button>
                    </form>
                </div>
            </div>
        </dialog>
    </div>



    <script>
        function openDeleteModal(itemId) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/cart/${itemId}`;

            document.getElementById('delete-modal').checked = true;
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            window.updateQty = function(button, delta) {
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

            // Tambahkan saat submit untuk bawa JSON
            document.querySelector('#cartForm').addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.item-check:checked');
                let data = [];

                checked.forEach(cb => {
                    data.push({
                        id: cb.value,
                        qty: cb.dataset.qty
                    });
                });

                document.querySelector('#itemsJson').value = JSON.stringify(data);
            });
        });
    </script>
@endsection
