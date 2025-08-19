@extends('layouts.app')

@section('content')
    <div x-data="payment()" x-init="init(window.methods)" class="max-w-5xl mx-auto px-4 py-8">

        <div class="grid md:grid-cols-2 gap-6" x-data="{ showModal: false }">

            <!-- KIRI: METODE PEMBAYARAN -->
            <div class="space-y-6">
                <div class="card bg-white shadow p-6 rounded-2xl">
                    <h2 class="text-xl font-semibold mb-4">Pilih Metode Pembayaran</h2>

                    <div class="space-y-4">
                        <template x-for="(list, category) in methods" :key="category">
                            <div>
                                <!-- Accordion Header -->
                                <button
                                    class="w-full flex justify-between items-center text-left py-2 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition"
                                    @click="openCategory = openCategory === category ? '' : category">
                                    <span class="font-semibold text-gray-700" x-text="category"></span>
                                    <svg :class="{ 'rotate-180': openCategory === category }"
                                        class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Accordion Content -->
                                <div x-show="openCategory === category" x-collapse class="mt-2 space-y-2">
                                    <template x-for="method in list" :key="method.id">
                                        <div @click="selected = method"
                                            class="p-4 border rounded-xl cursor-pointer hover:shadow transition-all"
                                            :class="{
                                                'border-blue-500 ring-1 ring-blue-300 bg-blue-50': selected?.id ===
                                                    method
                                                    .id
                                            }">
                                            <p class="font-medium text-gray-800" x-text="method.name"></p>
                                            <p class="text-sm text-gray-500" x-text="method.description"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- KANAN: INSTRUKSI -->
            <div class="card bg-white shadow p-6 rounded-2xl space-y-6">
                <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Instruksi Pembayaran</h2>

                <!-- VA -->
                <template x-if="selected.type === 'va'">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">Silakan transfer ke rekening berikut:</p>
                        <div
                            class="bg-gray-50 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Bank</p>
                                <p class="text-base font-semibold text-gray-800" x-text="selected.name"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">No. Virtual Account</p>
                                <p class="font-mono text-lg font-bold text-gray-800" x-text="selected.va_number"></p>
                            </div>
                            <button @click="navigator.clipboard.writeText(selected.va_number)"
                                class="btn btn-sm btn-outline btn-primary">
                                Salin
                            </button>
                        </div>
                    </div>
                </template>

                {{-- <!-- QRIS -->
                <template x-if="selected.type === 'qris'">
                    <div class="text-center space-y-2">
                        <img src="https://via.placeholder.com/180x180.png?text=QRIS" alt="QRIS"
                            class="mx-auto rounded-md">
                        <p class="text-sm text-gray-700">Scan kode QR di atas dengan aplikasi pembayaran favorit Anda.</p>
                    </div>
                </template> --}}

                <!-- eWallet -->
                <template x-if="selected.type === 'ewallet'">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">Silakan kirim ke nomor berikut:</p>
                        <div
                            class="bg-gray-50 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-base font-semibold text-gray-800" x-text="selected.name"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">No. E-wallet</p>
                                <p class="font-mono text-lg font-bold text-gray-800" x-text="selected.phone_number"></p>
                            </div>
                            <button @click="navigator.clipboard.writeText(selected.phone_number)"
                                class="btn btn-sm btn-outline btn-primary">
                                Salin
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Jumlah bayar -->
                <div class="space-y-2">
                    <p class="text-sm text-gray-600">Jumlah yang harus dibayar:</p>
                    <div class="bg-yellow-50 rounded-xl p-4 flex items-center justify-between">
                        <p class="text-2xl font-bold text-yellow-700">{{ $total }}</p>
                        <button onclick="navigator.clipboard.writeText({{ $total }})"
                            class="btn btn-sm btn-outline btn-warning">
                            Salin
                        </button>
                    </div>
                </div>

                <!-- Langkah Pembayaran -->
                <div class="bg-white p-4 rounded-xl shadow border border-gray-200" x-show="selected">
                    <p class="text-sm text-gray-600 mb-3">Langkah-langkah:</p>
                    <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                        <template x-for="step in selected.steps" :key="step">
                            <li x-text="step"></li>
                        </template>
                    </ol>
                </div>
                @if ($order->payment_proofs)
                    <div class="bg-green-50 rounded-xl p-4 shadow border border-green-200">
                        <p class="text-sm font-semibold text-green-700 mb-2">Bukti Pembayaran:</p>
                        <img src="{{ cloudinary_url($order->payment_proofs, 'image', 'w_500,q_auto') }}"
                            alt="Bukti Pembayaran" class="w-full max-w-xs rounded-lg border border-green-300 shadow-sm">
                    </div>
                @endif

                <div class="flex justify-between md:justify-end gap-2 pt-4 border-t border-gray-200">
                    <a href="{{ route('order.history') }}" class="btn btn-gradient-neutral">Bayar Nanti</a>
                    <button @click="showModal = true" class="btn btn-gradient-primary">Kirim Bukti</button>
                </div>

            </div>


            <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                x-transition>
                <div @click.outside="showModal = false" class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Upload Bukti Pembayaran</h2>

                    <!-- Form -->
                    <form method="POST" action="{{ route('payment.confirm', $order->order_code) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="selected_method" :value="selected?.type">
                        <!-- Upload Screenshot -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Upload Bukti (Screenshot)</label>
                            <input type="file" name="bukti_pembayaran" accept="image/*"
                                class="w-full border rounded px-3 py-2" required>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" @click="showModal = false"
                                class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white">
                                Kirim Bukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine.js -->
    <script>
        window.methods = @json($methods);
    </script>
    <script>
        function payment() {
            return {
                selected: null,
                openCategory: '',
                methods: {},
                init(data) {
                    this.methods = data;
                    // Pilih default pertama
                    for (let category in this.methods) {
                        if (this.methods[category]?.length > 0) {
                            this.selected = this.methods[category][0];
                            break;
                        }
                    }
                }
            }
        }
    </script>
@endsection
