{{-- resources/views/tentang-kami.blade.php --}}
@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">
    
    <h1 class="text-4xl font-bold text-sky-600 mb-8">Tentang Kami</h1>

    <div class="bg-white shadow-lg rounded-xl p-8 space-y-8 leading-relaxed">

        {{-- Intro --}}
        <section>
            <h2 class="text-2xl font-semibold flex items-center gap-2">
                👥 Tentang Kami
            </h2>
            <p class="mt-2 text-gray-700">
                <strong>Y-Aladzan.my.id</strong> – Platform Reseller Langsung dari Pabrik
            </p>
        </section>

        {{-- Siapa Kami --}}
        <section>
            <h2 class="text-2xl font-semibold flex items-center gap-2">🌱 Siapa Kami?</h2>
            <p class="mt-2 text-gray-700">
                Y-Aladzan.com adalah platform reseller berbasis web yang dikembangkan oleh Y-Aladzan Corporation, berdiri sejak tahun 2023. Kami hadir sebagai solusi digital bagi siapa pun yang ingin memulai bisnis tanpa harus produksi sendiri atau menyetok dalam jumlah besar.
            </p>
            <p class="mt-2 text-gray-700">
                Kami menghubungkan langsung reseller dengan produk original dari pabrik, sehingga menawarkan harga yang bersaing dan stabil di pasaran.
            </p>
        </section>

        {{-- Visi --}}
        <section>
            <h2 class="text-2xl font-semibold flex items-center gap-2">🎯 Visi Kami</h2>
            <p class="mt-2 text-gray-700">
                Menjadi pusat distribusi digital terpercaya untuk produk original pabrik, yang mendukung pertumbuhan reseller di seluruh Indonesia.
            </p>
        </section>

        {{-- Misi --}}
        <section>
            <h2 class="text-2xl font-semibold flex items-center gap-2">📌 Misi Kami</h2>
            <ul class="list-disc pl-6 text-gray-700 space-y-1 mt-2">
                <li>Memberikan akses mudah bagi siapa saja untuk memulai bisnis dari rumah.</li>
                <li>Menyediakan produk langsung dari pabrik untuk menjaga konsistensi harga dan kualitas.</li>
                <li>Mendukung reseller membangun brand sendiri melalui fitur custom label dan kemasan.</li>
                <li>Mengembangkan platform digital yang praktis, transparan, dan efisien.</li>
            </ul>
        </section>

        {{-- Perbedaan Kami --}}
        <section>
            <h2 class="text-2xl font-semibold flex items-center gap-2">💡 Apa yang Membuat Kami Berbeda?</h2>
            <ul class="list-disc pl-6 text-gray-700 space-y-1 mt-2">
                <li>📦 Produk langsung dari pabrik: Tanpa perantara, tanpa markup berlapis.</li>
                <li>💰 Minim persaingan harga antar reseller: Harga dasar seragam, transparan, dan sehat untuk jualan.</li>
                <li>🏷️ Custom brand: Reseller bisa menjual produk dengan nama brand mereka sendiri.</li>
                <li>🛒 Tanpa ribet: Semua sistem — dari katalog hingga checkout — tersedia secara online dan mudah digunakan.</li>
                <li>📊 Stok dan harga update otomatis: Tidak perlu tanya-tanya manual.</li>
            </ul>
        </section>

        {{-- Closing --}}
        <section>
            <p class="mt-2 text-gray-700">
                <strong>Y-Aladzan.my.id</strong> hadir sebagai partner digital bisnis Anda, bukan hanya platform jualan.
                Kami percaya bahwa ketika sistemnya sehat, semua bisa tumbuh bersama.
            </p>
        </section>

    </div>
</div>
@endsection
