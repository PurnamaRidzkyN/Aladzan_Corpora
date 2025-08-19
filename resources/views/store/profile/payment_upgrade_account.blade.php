@extends('layouts.app')

@section('title', 'Upgrade Account')

@section('content')
    <div x-data="upgradePayment()" x-init='init(@json($methods))' class="max-w-5xl mx-auto px-4 py-8">

        <div class="grid md:grid-cols-2 gap-6">

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

            <!-- KANAN: INSTRUKSI PEMBAYARAN -->
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
                        <p class="text-2xl font-bold text-yellow-700">Rp {{ number_format($finalPrice, 0, ',', '.') }}</p>
                        <button onclick="navigator.clipboard.writeText({{ $finalPrice }})"
                            class="btn btn-sm btn-outline btn-warning">
                            Salin
                        </button>
                    </div>

                    @if ($discount)
                        <p class="text-green-600 text-sm">
                            Diskon diterapkan:
                            {{ $discount->is_percent ? $discount->amount . '%' : 'Rp ' . number_format($discount->amount, 0, ',', '.') }}
                        </p>
                    @endif
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




                <div class="flex justify-between md:justify-end gap-2 pt-4 border-t border-gray-200">
                    <button @click="showModal = true" class="btn btn-gradient-primary">Kirim Bukti</button>
                </div>

            </div>


            <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                x-transition>
                <div @click.outside="showModal = false" class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Upload Bukti Pembayaran</h2>

                    <!-- Form -->
                    <form method="POST" action="{{ route('upgrade.account.payment.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Metode pembayaran yang dipilih -->
                        <input type="hidden" name="selected_method" :value="selected?.type">

                        <!-- ID plan yang dipilih -->
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                        <!-- Harga final -->
                        <input type="hidden" name="final_price" value="{{ $finalPrice }}">

                        <!-- Discount code, kalau ada -->
                        <input type="hidden" name="discount_code" value="{{ $discount->code ?? '' }}">

                        <!-- Discount amount, kalau ada -->
                        <input type="hidden" name="discount_amount" value="{{ $discountAmount ?? 0 }}">
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

    <script>
        function upgradePayment() {
            return {
                selected: null,
                openCategory: '',
                methods: {},
                showModal: false, // ← wajib
                init(data) {
                    this.methods = data;
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
