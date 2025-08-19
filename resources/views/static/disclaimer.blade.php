{{-- resources/views/disclaimer.blade.php --}}
@extends('layouts.app')

@section('title', 'Disclaimer / Penyangkalan')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="text-4xl font-bold text-sky-600 mb-6">⚠️ Disclaimer / Penyangkalan</h1>
    <p class="text-gray-600">Y-Aladzan.my.id</p>
    <p class="text-gray-500 mb-8">Terakhir diperbarui: 2 Agustus 2025</p>

    <div class="bg-white shadow-lg rounded-xl p-8 space-y-8 leading-relaxed">

        <p>Dokumen ini berfungsi sebagai penyangkalan resmi dari pihak pengelola <strong>Y-Aladzan.my.id</strong>, yang dikelola oleh Y-Aladzan Corporation, atas hal-hal yang berkaitan dengan penggunaan platform oleh reseller maupun pihak ketiga lainnya. Dengan menggunakan platform ini, Anda dianggap telah membaca dan menyetujui isi disclaimer berikut:</p>

        {{-- 1. Batasan Tanggung Jawab --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">1. Batasan Tanggung Jawab</h2>
            <p><strong>Y-Aladzan.my.id</strong> merupakan platform digital yang menyediakan sistem, katalog, dan alat bantu operasional bagi para reseller. Platform ini bukan pihak yang bertanggung jawab secara langsung atas proses jual-beli antara reseller dan pelanggan akhir.</p>
            <p class="mt-2">Seluruh transaksi antara reseller dan pembeli dilakukan di luar tanggung jawab platform, termasuk namun tidak terbatas pada:</p>
            <ul class="list-disc pl-6 text-gray-700 mt-2 space-y-1">
                <li>Harga jual akhir</li>
                <li>Pengiriman kepada pembeli</li>
                <li>Keluhan, retur, atau permintaan pengembalian dana dari pembeli</li>
            </ul>
        </section>

        {{-- 2. Penyangkalan atas Kerugian --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">2. Penyangkalan atas Kerugian</h2>
            <p>Y-Aladzan Corporation tidak bertanggung jawab atas segala kerugian yang timbul akibat:</p>
            <ul class="list-disc pl-6 text-gray-700 mt-2 space-y-1">
                <li>Kesalahan penggunaan sistem oleh pengguna</li>
                <li>Ketidaktelitian saat checkout, input data, atau pengelolaan katalog</li>
                <li>Gangguan teknis, seperti kesalahan koneksi, kehilangan data lokal, atau error perangkat pengguna</li>
                <li>Kegagalan dalam memenuhi ekspektasi pelanggan akhir reseller</li>
            </ul>
            <p class="mt-2">Semua penggunaan platform dilakukan atas risiko pengguna sendiri.</p>
        </section>

        {{-- 3. Perubahan Fitur dan Sistem --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">3. Perubahan Fitur dan Sistem</h2>
            <p><strong>Y-Aladzan.my.id</strong> dapat melakukan pembaruan, perbaikan, atau penghapusan fitur dari waktu ke waktu untuk alasan teknis, fungsional, atau kebijakan internal.</p>
            <p class="mt-2">Kami tidak berkewajiban memberi pemberitahuan terlebih dahulu atas:</p>
            <ul class="list-disc pl-6 text-gray-700 mt-2 space-y-1">
                <li>Penambahan atau pengurangan fitur</li>
                <li>Perubahan tampilan antarmuka</li>
                <li>Penyesuaian kebijakan harga, sistem poin, atau skema bonus</li>
            </ul>
            <p class="mt-2">Kami berupaya menjaga agar sistem tetap berfungsi optimal, namun tidak menjamin ketersediaan 100% (non-stop) setiap saat.</p>
        </section>

        {{-- 4. Hak Kekayaan Intelektual --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">4. Hak Kekayaan Intelektual</h2>
            <p>Seluruh konten yang ditampilkan di platform (logo, desain, sistem, deskripsi, dan media visual) adalah milik Y-Aladzan Corporation atau pemilik sahnya, dan tidak boleh digunakan kembali tanpa izin tertulis.</p>
        </section>

        {{-- Penutup --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">📌 Penutup</h2>
            <p>Dengan menggunakan platform Y-Aladzan.my.id, Anda menerima bahwa seluruh risiko penggunaan sistem berada di tangan pengguna sendiri. Kami menyarankan agar pengguna memahami batasan tanggung jawab platform sebagaimana dijelaskan dalam dokumen ini.</p>
            <p class="mt-2">Untuk pertanyaan lebih lanjut, hubungi kami melalui halaman <a href="{{ url('/kontak') }}" class="text-sky-500 hover:underline">Kontak Kami</a>.</p>
        </section>

    </div>
</div>
@endsection
