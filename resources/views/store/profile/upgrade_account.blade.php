@extends('layouts.app')
@section('title', 'Upgrade Account')

@section('content')
<div class="flex items-center justify-center px-4 md:px-0">
    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 max-w-3xl w-full border border-blue-100">
        <h1 class="text-2xl md:text-3xl font-bold text-center text-blue-700 mb-4 md:mb-6">Tingkatkan Paket Anda</h1>
        <p class="text-center text-gray-600 mb-4 text-sm md:text-base">Akun Anda saat ini menggunakan paket <strong>Standard</strong></p>

        <!-- Input Kode Diskon -->
        <div class="mb-6 text-center">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <input id="kodeDiskon" type="text" placeholder="Masukkan kode diskon..."
                    class="w-full sm:w-64 px-4 py-2 border border-blue-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button onclick="cekDiskon()"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm font-medium transition">
                    Terapkan
                </button>
            </div>
            <p id="pesanDiskon" class="mt-2 text-sm hidden"></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Paket Standard (Aktif) -->
            <div class="border rounded-xl p-4 md:p-6 bg-gray-100 opacity-70">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg md:text-xl font-bold text-gray-500">Standard (Aktif)</h2>
                    <span class="text-sm bg-gray-300 text-gray-600 px-3 py-1 rounded-full">Rp 0</span>
                </div>
                <ul class="space-y-2 text-gray-500 text-sm">
                    <li>✅ Akses dasar ke fitur</li>
                    <li>✅ Support via Email</li>
                    <li>✅ 5 GB Storage</li>
                </ul>
            </div>

            <!-- Paket Pro -->
            <div class="border rounded-xl p-4 md:p-6 bg-white hover:shadow-lg hover:scale-[1.02] transition cursor-pointer">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg md:text-xl font-bold text-blue-700">Pro</h2>
                    <div class="text-right">
                        <span id="hargaAsli" class="block text-gray-400 text-sm line-through hidden">Rp 99.000</span>
                        <span id="hargaPro" class="text-sm md:text-base bg-blue-600 text-white px-3 py-1 rounded-full">Rp 99.000</span>
                        <span id="diskonInfo" class="block text-green-600 text-xs font-medium mt-1 hidden"></span>
                    </div>
                </div>
                <ul class="space-y-2 text-gray-600 text-sm">
                    <li>🚀 Semua fitur Standard</li>
                    <li>🚀 Prioritas Support 24/7</li>
                    <li>🚀 100 GB Storage</li>
                    <li>🚀 Custom Branding</li>
                </ul>
                <button
                    class="mt-4 md:mt-6 w-full py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                    Upgrade ke Pro
                </button>
            </div>
        </div>

        <p class="text-center text-gray-500 text-xs mt-6">
            Anda dapat mengubah paket kapan saja di pengaturan akun.
        </p>

        <div class="text-center mt-4">
            <a href="/" class="inline-block text-blue-600 hover:underline text-sm font-medium">Lanjutkan ke Beranda</a>
        </div>
    </div>
</div>

<!-- Script cek diskon -->
<script>
function cekDiskon() {
    const kode = document.getElementById('kodeDiskon').value;
    const pesan = document.getElementById('pesanDiskon');
    const hargaPro = document.getElementById('hargaPro');
    const hargaAsli = document.getElementById('hargaAsli');
    const diskonInfo = document.getElementById('diskonInfo');
    const hargaNormal = 99000;

    fetch(`/check-discount/${encodeURIComponent(kode)}`)
        .then(res => res.json())
        .then(data => {
            pesan.classList.remove('hidden', 'text-green-600', 'text-red-600');

            if (data.valid) {
                pesan.textContent = data.message;
                pesan.classList.add('text-green-600');

                const hargaAkhir = data.is_percent
                    ? hargaNormal - (hargaNormal * data.amount / 100)
                    : hargaNormal - data.amount;

                const hemat = hargaNormal - hargaAkhir;

                hargaPro.textContent = `Rp ${hargaAkhir.toLocaleString('id-ID')}`;
                hargaAsli.classList.remove('hidden');
                diskonInfo.classList.remove('hidden');
                diskonInfo.textContent = data.is_percent 
                    ? `Diskon ${data.amount}% (Hemat Rp ${hemat.toLocaleString('id-ID')})`
                    : `Potongan langsung Rp ${hemat.toLocaleString('id-ID')}`;

            } else {
                pesan.textContent = data.message;
                pesan.classList.add('text-red-600');

                hargaPro.textContent = 'Rp 99.000';
                hargaAsli.classList.add('hidden');
                diskonInfo.classList.add('hidden');
            }
        })
        .catch(() => {
            pesan.textContent = 'Terjadi kesalahan saat memeriksa kode diskon';
            pesan.classList.remove('hidden');
            pesan.classList.add('text-red-600');
        });
}
</script>
@endsection
