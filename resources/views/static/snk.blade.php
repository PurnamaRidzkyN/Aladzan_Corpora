@extends('layouts.app')

@section('title', 'Syarat & Ketentuan')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold text-sky-600 mb-8">📝  Syarat & Ketentuan</h1>
    <p class="text-gray-500 mb-8">Terakhir diperbarui: 2 Agustus 2025</p>
    <div class="bg-white shadow-lg rounded-xl p-8 space-y-8">

        <p>Selamat datang di platform reseller <strong>Y-Aladzan.my.id</strong>. Dengan mendaftar dan menggunakan platform ini, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh isi Syarat dan Ketentuan berikut.</p>

        {{-- 1. Definisi Pengguna --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-2">1. Definisi Pengguna</h2>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li><strong>Reseller</strong> adalah individu atau badan yang telah mendaftar dan memiliki akun resmi untuk menjual produk melalui platform Y-Aladzan.my.id.</li>
                <li><strong>Admin</strong> adalah pihak pengelola sistem dan konten platform, yang memiliki kewenangan teknis dan operasional.</li>
                <li><strong>Platform</strong> adalah situs web dan sistem digital milik Y-Aladzan Corporation yang digunakan untuk manajemen reseller, katalog produk, dan transaksi.</li>
            </ul>
        </section>

        {{-- 2. Aturan Pendaftaran dan Penggunaan Akun --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-2">2. Aturan Pendaftaran dan Penggunaan Akun</h2>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Setiap pengguna wajib mengisi data secara benar dan jujur saat pendaftaran.</li>
                <li>Reseller bertanggung jawab menjaga kerahasiaan akun dan kata sandi mereka.</li>
                <li>Akun hanya boleh digunakan oleh pihak yang mendaftarkan diri dan tidak dapat dipindahtangankan tanpa persetujuan admin.</li>
                <li>Dilarang membuat lebih dari satu akun tanpa izin resmi dari admin.</li>
            </ul>
        </section>

        {{-- 3. Hak dan Kewajiban Pengguna (Reseller) --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-2">3. Hak dan Kewajiban Pengguna (Reseller)</h2>
            <p class="font-semibold">Reseller berhak:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Mengakses katalog produk dan fitur sistem yang disediakan.</li>
                <li>Menggunakan akun reseller untuk kegiatan promosi dan penjualan kembali produk.</li>
                <li>Mendapatkan dukungan teknis dari tim admin selama jam operasional.</li>
            </ul>
            <p class="font-semibold mt-4">Reseller berkewajiban:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Menggunakan sistem secara bertanggung jawab dan sesuai dengan fungsinya.</li>
                <li>Tidak menyalahgunakan konten katalog tanpa izin.</li>
                <li>Tidak merugikan atau mencemarkan nama baik platform, brand, atau reseller lain.</li>
                <li>Memberikan informasi yang benar dalam setiap proses transaksi.</li>
                <li>Mematuhi harga minimal penjualan jika ditentukan oleh pihak Y-Aladzan Corporation.</li>
            </ul>
        </section>

        {{-- 4. Hak dan Kewajiban Pemilik Platform (Admin) --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-2">4. Hak dan Kewajiban Pemilik Platform (Admin)</h2>
            <p class="font-semibold">Y-Aladzan Corporation berhak:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Melakukan pembaruan sistem, fitur, dan kebijakan platform sewaktu-waktu tanpa pemberitahuan.</li>
                <li>Menangguhkan atau menghapus akun pengguna yang melanggar ketentuan ini.</li>
                <li>Meninjau dan menghapus konten atau aktivitas yang melanggar aturan platform.</li>
            </ul>
            <p class="font-semibold mt-4">Y-Aladzan Corporation berkewajiban:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Menyediakan sistem yang stabil dan terus diperbarui.</li>
                <li>Melindungi data pengguna sesuai dengan Kebijakan Privasi.</li>
                <li>Menyediakan bantuan teknis yang wajar sesuai kapasitas operasional.</li>
            </ul>
        </section>

        {{-- 5. Kebijakan Pembatalan dan Penangguhan Akun --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-2">5. Kebijakan Pembatalan dan Penangguhan Akun</h2>
            <p>Akun reseller dapat ditangguhkan atau dibatalkan jika:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Terbukti menyalahgunakan sistem atau melakukan aktivitas penipuan.</li>
                <li>Menggunakan identitas palsu atau informasi menyesatkan.</li>
                <li>Melanggar aturan secara berulang atau berat.</li>
            </ul>
            <p>Pengguna dapat mengajukan banding ke pihak admin melalui kontak resmi yang tersedia.</p>
        </section>

        {{-- 6. Larangan Penggunaan --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-2">6. Larangan Penggunaan</h2>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Menyebarluaskan ulang konten katalog tanpa izin.</li>
                <li>Mengunggah atau menyebarkan konten negatif, SARA, penipuan, atau merusak reputasi.</li>
                <li>Mencoba meretas sistem atau merusak fitur.</li>
                <li>Menjual akun kepada pihak ketiga tanpa persetujuan admin.</li>
            </ul>
        </section>

        {{-- 7. Penyelesaian Sengketa --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-2">7. Penyelesaian Sengketa</h2>
            <p>Perselisihan akan diselesaikan terlebih dahulu melalui musyawarah antara pengguna dan pihak Y-Aladzan Corporation. Jika tidak tercapai kesepakatan, penyelesaian dilanjutkan melalui jalur hukum Republik Indonesia.</p>
            <p>Semua data komunikasi dan catatan transaksi digital dapat digunakan sebagai bukti.</p>
        </section>

        {{-- Penutup --}}
        <section>
            <h2 class="text-2xl font-semibold text-sky-600 mb-2">📌 Penutup</h2>
            <p>Dengan menggunakan platform Y-Aladzan.my.id, Anda dianggap telah menyetujui seluruh isi Syarat dan Ketentuan ini. Kami menyarankan agar Anda membaca seluruh isi dokumen ini secara menyeluruh sebelum menggunakan layanan.</p>
            <p>Untuk pertanyaan lebih lanjut, hubungi kami melalui halaman <a href="/kontak" class="text-sky-500 hover:underline">Kontak Kami</a> di situs resmi.</p>
        </section>

    </div>
</div>
@endsection
