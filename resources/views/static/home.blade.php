<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gabung Reseller - ALADZAN CORPORA</title>

    <!-- SEO -->
    <meta name="description" content="Gabung jadi reseller Aladzan Corpora. Peluang bisnis mudah, margin besar, produk original & katalog mitra terpercaya. Cocok untuk pemula maupun profesional.">
    <meta name="keywords" content="reseller, dropship, bisnis online, Aladzan Corpora, reseller Indonesia, produk original">
    <meta name="author" content="ALADZAN CORPORA">

    <!-- Open Graph -->
    <meta property="og:title" content="Gabung Jadi Reseller - ALADZAN CORPORA" />
    <meta property="og:description" content="Peluang bisnis mudah, margin besar, produk original & katalog mitra terpercaya. Gabung sekarang!" />
    <meta property="og:image" content="{{ asset('storage/logo1.png') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/logo1.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/logo1.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/logo1.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/logo1.png') }}">


    <link rel="icon" type="image/png" href="{{ asset('storage/logo2.png') }}">

    <!-- Tailwind & Font Awesome -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-sky-50 text-gray-800 font-sans ">

    <!-- NAV -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex-shrink-0">
                <img src="{{ asset('storage/logo2.png') }}" alt="ALADZAN CORPORA Logo"
                     class="w-24 sm:w-32 md:w-48 max-w-full h-auto object-contain">
            </a>
            <a href="/login" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors">
                Login / Daftar Reseller
            </a>
        </div>
    </nav>

    <main>
        <!-- HERO -->
        <section class="py-20 md:py-28 ">
            <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                        Gabung Jadi Reseller Aladzan Corpora
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        Peluang bisnis mudah, margin besar, produk original & katalog mitra terpercaya.
                    </p>
                    <a href="#paket" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-lg font-semibold transition-transform transform hover:scale-105">
                        Lihat Pilihan Paket
                    </a>
                </div>
                <div class="bg-gray-200 rounded-lg shadow-lg flex items-center justify-center h-80 md:h-96">
                    <img src="{{ asset('storage/home.png') }}" alt="Produk Aladzan Corpora" class="max-h-full object-contain">
                </div>
            </div>
        </section>

        <!-- KEUNGGULAN -->
        <section class="bg-white py-20">
            <div class="max-w-6xl mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Kenapa Jadi Reseller Aladzan Corpora?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto mb-12">Dapatkan berbagai keuntungan eksklusif yang dirancang untuk mendukung pertumbuhan bisnis Anda.</p>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="p-6 border border-gray-200 rounded-lg hover:shadow-xl transition-shadow">
                        <i class="fas fa-gem text-3xl text-blue-600 mb-4"></i>
                        <h3 class="font-semibold text-lg mb-2">Produk Original</h3>
                        <p class="text-gray-600 text-sm">Jual produk asli dari Aladzan Corpora dan nikmati margin lebih besar.</p>
                    </div>
                    <div class="p-6 border border-gray-200 rounded-lg hover:shadow-xl transition-shadow">
                        <i class="fas fa-book-open text-3xl text-blue-600 mb-4"></i>
                        <h3 class="font-semibold text-lg mb-2">Katalog Mitra</h3>
                        <p class="text-gray-600 text-sm">Akses katalog produk dari mitra terpercaya untuk pilihan lebih beragam.</p>
                    </div>
                    <div class="p-6 border border-gray-200 rounded-lg hover:shadow-xl transition-shadow">
                        <i class="fas fa-shipping-fast text-3xl text-blue-600 mb-4"></i>
                        <h3 class="font-semibold text-lg mb-2">Bisa Dropship</h3>
                        <p class="text-gray-600 text-sm">Kirim produk langsung dari gudang kami ke pelanggan Anda.</p>
                    </div>
                    <div class="p-6 border border-gray-200 rounded-lg hover:shadow-xl transition-shadow">
                        <i class="fas fa-share-alt text-3xl text-blue-600 mb-4"></i>
                        <h3 class="font-semibold text-lg mb-2">Materi & Komunitas</h3>
                        <p class="text-gray-600 text-sm">Akses materi promosi eksklusif & komunitas reseller premium.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PAKET -->
        <section id="paket" class="py-20">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Pilihan Paket Reseller</h2>
                <p class="text-gray-600 max-w-2xl mx-auto mb-12">Pilih paket sesuai kebutuhan bisnis Anda.</p>
                <div class="grid md:grid-cols-2 gap-8 items-start">
                    <!-- Basic -->
                    <div class="bg-white p-8 rounded-xl shadow-lg border">
                        <h3 class="text-2xl font-bold mb-2">Basic</h3>
                        <p class="text-4xl font-bold mb-6">Rp 165K</p>
                        <ul class="space-y-4 text-left mb-8">
                            <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-3"></i>Akses Katalog Mitra</li>
                            <li class="flex items-center"><i class="fas fa-times-circle mr-3"></i>Materi Promosi</li>
                            <li class="flex items-center"><i class="fas fa-times-circle mr-3"></i>Grup Reseller</li>
                        </ul>
                        <a href="{{ route('register') }}" class="w-full block text-center py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold transition-colors">Pilih Paket Basic</a>
                    </div>
                    <!-- Premium -->
                    <div class="bg-white p-8 rounded-xl shadow-2xl border-2 border-blue-600 relative">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold">
                            Paling Populer
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Premium</h3>
                        <p class="text-4xl font-bold mb-6">Rp 399K</p>
                        <ul class="space-y-4 text-left mb-8">
                            <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-3"></i>Akses Katalog Mitra</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-3"></i>Materi Promosi</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-3"></i>Grup Reseller</li>
                        </ul>
                        <a href="{{ route('register') }}" class="w-full block text-center py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors">Pilih Paket Premium</a>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-6">*Benefit dapat berubah sesuai kebijakan perusahaan.</p>
            </div>
        </section>
    </main>

    @include('layouts.footer')

</body>
</html>
