{{-- resources/views/kebijakan-privasi.blade.php --}}
@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="text-4xl font-bold text-sky-600 mb-6">🔒 Kebijakan Privasi</h1>
    <p class="text-gray-500 mb-8">Terakhir diperbarui: 2 Agustus 2025</p>

    <div class="bg-white shadow-lg rounded-xl p-8 space-y-8 leading-relaxed">

        <p>Privasi Anda penting bagi kami. Dokumen ini menjelaskan bagaimana <strong>Y-Aladzan.my.id</strong>, yang dikelola oleh Y-Aladzan Corporation, mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi pengguna (reseller) yang terdaftar di platform kami. Dengan menggunakan layanan kami, Anda menyetujui ketentuan dalam kebijakan ini.</p>

        {{-- 1. Data yang Kami Kumpulkan --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">1. Data yang Kami Kumpulkan</h2>
            <p>Kami mengumpulkan data pribadi pengguna saat proses pendaftaran, penggunaan sistem, dan interaksi dengan fitur platform. Data yang dikumpulkan meliputi:</p>
            <ul class="list-disc pl-6 text-gray-700 mt-2 space-y-1">
                <li>Nama lengkap</li>
                <li>Nomor WhatsApp (WA)</li>
                <li>Alamat email</li>
                <li>Alamat pengiriman</li>
                <li>Riwayat aktivitas di platform (keranjang, checkout, dll)</li>
            </ul>
        </section>

        {{-- 2. Cara Kami Menggunakan Data --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">2. Cara Kami Menggunakan Data</h2>
            <p>Data pribadi yang dikumpulkan akan digunakan untuk:</p>
            <ul class="list-disc pl-6 text-gray-700 mt-2 space-y-1">
                <li>Memverifikasi identitas dan mengaktifkan akun reseller</li>
                <li>Mengelola dan menampilkan katalog produk</li>
                <li>Memproses pengiriman dan checkout</li>
                <li>Menyediakan bantuan teknis dan layanan pelanggan</li>
                <li>Mengirimkan informasi promosi, update sistem, atau pengumuman penting (via WA atau email)</li>
            </ul>
            <p class="mt-2">Kami tidak akan menjual, menyewakan, atau membagikan data pribadi Anda untuk keperluan komersial tanpa izin Anda, kecuali sebagaimana dijelaskan dalam kebijakan ini.</p>
        </section>

        {{-- 3. Penyimpanan dan Keamanan Data --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">3. Penyimpanan dan Keamanan Data</h2>
            <ul class="list-disc pl-6 text-gray-700 mt-2 space-y-1">
                <li>Data disimpan secara digital di sistem yang dikelola oleh Y-Aladzan Corporation.</li>
                <li>Kami menerapkan langkah-langkah keamanan teknis untuk melindungi data dari akses ilegal, pencurian, atau kebocoran.</li>
                <li>Meski demikian, kami tidak dapat menjamin sepenuhnya keamanan data jika terjadi pelanggaran keamanan yang berada di luar kendali kami (misal: serangan siber pihak ketiga).</li>
            </ul>
        </section>

        {{-- 4. Hak Pengguna atas Data --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">4. Hak Pengguna atas Data</h2>
            <p>Sebagai pengguna, Anda memiliki hak:</p>
            <ul class="list-disc pl-6 text-gray-700 mt-2 space-y-1">
                <li>Mengakses dan melihat data pribadi Anda yang tersimpan</li>
                <li>Memperbarui atau mengoreksi informasi pribadi yang salah</li>
                <li>Meminta penghapusan akun dan seluruh data pribadi (dengan konsekuensi kehilangan akses ke layanan)</li>
                <li>Menolak menerima pesan promosi dari kami (opt-out)</li>
            </ul>
            <p class="mt-2">Permintaan tersebut dapat diajukan melalui kontak resmi admin yang tersedia.</p>
        </section>

        {{-- 5. Akses oleh Pihak Ketiga --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">5. Akses oleh Pihak Ketiga</h2>
            <p>Kami tidak memberikan akses data pribadi kepada pihak ketiga, kecuali jika:</p>
            <ul class="list-disc pl-6 text-gray-700 mt-2 space-y-1">
                <li>Dibutuhkan oleh penyedia layanan teknis (misalnya layanan hosting atau payment gateway), dan mereka juga tunduk pada kewajiban kerahasiaan.</li>
                <li>Diwajibkan oleh hukum, perintah pengadilan, atau proses hukum lainnya.</li>
                <li>Diperlukan untuk mencegah penipuan, penyalahgunaan, atau ancaman terhadap sistem dan pengguna lain.</li>
            </ul>
        </section>

        {{-- Penutup --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-3">📌 Penutup</h2>
            <p>Dengan mendaftar dan menggunakan Y-Aladzan.my.id, Anda menyetujui pengumpulan dan penggunaan data Anda sebagaimana dijelaskan dalam kebijakan ini. Kami dapat memperbarui Kebijakan Privasi ini sewaktu-waktu. Perubahan akan diinformasikan melalui halaman ini atau pemberitahuan khusus.</p>
            <p class="mt-2">Untuk pertanyaan lebih lanjut terkait privasi dan data Anda, silakan hubungi admin kami melalui halaman <a href="{{ url('/kontak') }}" class="text-sky-500 hover:underline">Kontak Kami</a>.</p>
        </section>

    </div>
</div>
@endsection
