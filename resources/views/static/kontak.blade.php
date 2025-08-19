{{-- resources/views/kontak.blade.php --}}
@extends('layouts.app')

@section('title', 'Kontak & Layanan Pelanggan')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    <h1 class="text-4xl font-bold text-sky-600 mb-6">📞 Kontak & Layanan Pelanggan</h1>
    <p class="text-gray-600 mb-8">Kami siap membantu Anda dalam berbagai kebutuhan terkait penggunaan platform kami.</p>

    {{-- Layanan yang Kami Tanggapi --}}
    <div class="bg-white shadow-lg rounded-xl p-6 mb-8">
        <h2 class="text-2xl font-semibold flex items-center gap-2 mb-4">🔧 Layanan yang Kami Tanggapi</h2>
        <ul class="list-disc pl-6 text-gray-700 space-y-1">
            <li>Bantuan teknis seputar penggunaan platform, keranjang, checkout, dan akun Anda.</li>
            <li>Permintaan bantuan login, termasuk lupa email atau kendala akses akun.</li>
            <li>Pelaporan masalah katalog atau produk, seperti deskripsi, stok, atau tampilan yang tidak sesuai.</li>
            <li>Usulan kerja sama, baik untuk supplier, pabrik, atau pihak yang ingin menjual produknya di platform kami.</li>
        </ul>
    </div>

    {{-- Hubungi Kami --}}
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-semibold flex items-center gap-2 mb-4">📬 Hubungi Kami</h2>
        <div class="space-y-3 text-gray-700">
            <p>📧 <strong>Email Resmi:</strong> <a href="mailto:y.aladzan.92@gmail.com" class="text-sky-500 hover:underline">y.aladzan.92@gmail.com</a></p>
            <p>🌐 <strong>Website:</strong> <a href="https://Y-Aladzan.my.id" target="_blank" class="text-sky-500 hover:underline">Y-Aladzan.my.id</a></p>
            <p>📱 <strong>Instagram / TikTok / Facebook:</strong> <span class="text-sky-500">@y.aladzan</span></p>
            <p class="text-sm text-gray-500">Kami berusaha membalas setiap pesan dalam waktu maksimal 1 x 24 jam pada hari kerja.</p>
        </div>
    </div>

</div>
@endsection
