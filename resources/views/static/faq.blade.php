{{-- resources/views/faq.blade.php --}}
@extends('layouts.app')

@section('title', 'FAQ - Pertanyaan yang Sering Diajukan')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    <h1 class="text-4xl font-bold text-sky-600 mb-8">❓ Pertanyaan yang Sering Diajukan (FAQ)</h1>
    <p class="mb-6 text-gray-600">Berikut adalah daftar pertanyaan yang sering diajukan terkait platform <strong>Y-Aladzan.my.id</strong>.</p>

    <div class="space-y-4">

        <div class="collapse collapse-arrow bg-white shadow rounded-lg">
            <input type="checkbox" />
            <div class="collapse-title text-lg font-medium">
                1. Bagaimana cara menjadi reseller di Y-Aladzan.my.id?
            </div>
            <div class="collapse-content text-gray-700">
                <p>Cukup daftar melalui halaman pendaftaran resmi di website kami. Setelah akun Anda aktif, Anda dapat langsung mengakses katalog, menambahkan produk ke keranjang, dan mulai jualan.</p>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-white shadow rounded-lg">
            <input type="checkbox" />
            <div class="collapse-title text-lg font-medium">
                2. Apakah saya bisa menggunakan nama brand sendiri untuk produk yang dijual?
            </div>
            <div class="collapse-content text-gray-700">
                <p>Ya. Platform kami mendukung sistem custom label, sehingga Anda bisa menjual produk dengan merek pribadi Anda sendiri.</p>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-white shadow rounded-lg">
            <input type="checkbox" />
            <div class="collapse-title text-lg font-medium">
                3. Berapa biaya untuk menjadi reseller setelah masa promo launching?
            </div>
            <div class="collapse-content text-gray-700">
                <p>Biaya aktivasi akun reseller setelah launching adalah Rp150.000 (sekali bayar). Namun, Anda bisa menggunakan kode promo dari Mitra resmi kami untuk mendapatkan diskon atau potongan khusus saat pendaftaran.</p>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-white shadow rounded-lg">
            <input type="checkbox" />
            <div class="collapse-title text-lg font-medium">
                4. Seberapa sering stok dan katalog diperbarui?
            </div>
            <div class="collapse-content text-gray-700">
                <p>Kami memperbarui stok dan katalog secara berkala dan real-time, langsung dari pabrik. Anda tidak perlu menanyakan stok secara manual.</p>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-white shadow rounded-lg">
            <input type="checkbox" />
            <div class="collapse-title text-lg font-medium">
                5. Apakah sistem bisa digunakan dari HP atau hanya lewat komputer?
            </div>
            <div class="collapse-content text-gray-700">
                <p>Sistem Y-Aladzan.my.id sepenuhnya mobile-friendly dan dapat diakses dengan lancar melalui smartphone.my.id>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-white shadow rounded-lg">
            <input type="checkbox" />
            <div class="collapse-title text-lg font-medium">
                6. Apakah saya bisa memesan produk secara satuan?
            </div>
            <div class="collapse-content text-gray-700">
                <p>Ya. Anda bisa mulai bisnis tanpa harus menyetok banyak barang karena kami mendukung sistem tanpa minimal order.</p>
            </div>
        </div>

        <div class="collapse collapse-arrow bg-white shadow rounded-lg">
            <input type="checkbox" />
            <div class="collapse-title text-lg font-medium">
                7. Jika saya mengalami kendala saat login atau menggunakan platform, harus hubungi ke mana?
            </div>
            <div class="collapse-content text-gray-700">
                <p>Silakan kirim email ke <a href="mailto:y.aladzan.92@gmail.com" class="text-sky-500 hover:underline">y.aladzan.92@gmail.com</a>. Sertakan deskripsi masalah dan, jika perlu, tangkapan layar agar kami bisa membantu dengan lebih cepat.</p>
            </div>
        </div>

    </div>
</div>
@endsection
