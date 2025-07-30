<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'ResellerShop')</title>

    {{-- Gunakan Vite untuk CSS dan JS --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

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


<body class="bg-sky-50 text-gray-800  font-sans " x-data="{ open: false, openUser: false, openKategori: false }">
    <!-- 🔼 Navbar Atas -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
            <!-- Logo -->
            <a href="/" class="text-xl font-bold text-blue-600 hover:text-blue-700 transition">
                Reseller<span class="text-gray-800">Shop</span>
            </a>

            <!-- Search Form -->
            <form action="/search" method="GET" class="flex-1 flex">
                <input type="text" name="q" placeholder="Cari produk..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-l-xl focus:ring-2 focus:ring-blue-300 outline-none" />
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-xl hover:bg-blue-700">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
            <div class="hidden md:flex items-center space-x-4">
                {{-- Ikon Wishlist --}}
                <a href="{{ route('favorite') }}" class="hover:text-pink-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                    </svg>
                </a>
            </div>
            <!-- Menu -->
            <div class="hidden md:flex items-center gap-4">
                <!-- Cart -->
                <a href="/cart"
                    class="group relative flex items-center justify-center text-gray-700 hover:text-blue-600">
                    <i class="fas fa-shopping-cart"></i>

                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2 9h14l-2-9" />
                    </svg>

                </a>


                <!-- Profil -->
                <div x-data="{ openUser: false }" class="hidden md:block relative">
                    <button @click="openUser = !openUser"
                        class="w-9 h-9 rounded-full overflow-hidden flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-700">
                        @php
                            $adminLoggedIn = Auth::guard('admin')->check();
                        @endphp

                        @if ($adminLoggedIn)
                            {{-- Admin Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A4 4 0 0112 16a4 4 0 016.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        @elseif(auth()->check())
                            {{-- Reseller Profile --}}
                            
                            <img src="{{ cloudinary_url(auth()->user()->pfp_path) }}" alt="Profil"
                                class="w-full h-full object-cover rounded-full">
                        @else
                            {{-- Belum login --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A4 4 0 0112 16a4 4 0 016.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        @endif
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="openUser" @click.away="openUser = false" x-cloak
                        class="absolute right-0 mt-2 w-44 bg-white shadow-lg rounded-lg p-3 z-10 text-sm">
                        @if ($adminLoggedIn)
                            <a href="/admin/dashboard"
                                class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Dashboard</a>
                            <a href="/logout" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Logout</a>
                        @elseif(auth()->check())
                            <div class="px-3 py-2 text-gray-900 font-semibold truncate">{{ auth()->user()->name }}</div>
                            <a href="/profil" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Profil
                                Saya</a>
                            <a href="/logout" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Logout</a>
                        @else
                            <a href="/login" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Login /
                                Daftar</a>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </nav>

    <!-- 🔽 Mobile Bottom Nav -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t z-50 shadow">
        <div class="flex justify-around items-center h-16 text-sm text-gray-700">
            {{-- Home --}}
            <a href="/" class="flex flex-col items-center hover:text-blue-600">
                <svg class="h-5 w-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2 7-7 7 7 2 2" />
                </svg>
                Home
            </a>

            {{-- Ikon Wishlist --}}
            <a href="{{ route('favorite') }}" class="flex flex-col items-center hover:text-pink-600">
                <svg class="h-5 w-5 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                </svg>
                Favorit
            </a>

            {{-- Cart --}}
            <a href="/cart" class="relative flex flex-col items-center hover:text-blue-600">
                <i class="fas fa-shopping-cart"></i>

                Cart
            </a>

            {{-- Profil / Login --}}
            @php
                $adminLoggedIn = Auth::guard('admin')->check();
            @endphp

            @if ($adminLoggedIn)
                {{-- Kalau admin login, arahkan ke dashboard --}}
                <a href="/admin/dashboard" class="flex flex-col items-center hover:text-blue-600">
                    <svg class="h-5 w-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A4 4 0 0112 16a4 4 0 016.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            @elseif(auth()->check())
                {{-- Kalau reseller login --}}
                <a href="/profil" class="flex flex-col items-center hover:text-blue-600">
                    <img src="{{ cloudinary_url(auth()->user()->pfp_path) }}"
                        class="h-5 w-5 mb-1 rounded-full object-cover" alt="Profil">
                    <span>Profil</span>
                </a>
            @else
                {{-- Belum login --}}
                <a href="/login" class="flex flex-col items-center hover:text-blue-600">
                    <svg class="h-5 w-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A4 4 0 0112 16a4 4 0 016.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Profil</span>
                </a>
            @endif

        </div>
    </div>


    {{-- Konten halaman --}}
    <div class=" p-10" >
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="mx-auto max-w-md mb-4  
        rounded-lg shadow-md px-6 py-3 flex items-center space-x-3
        text-green-900 bg-gradient-to-r from-green-100 via-green-50 to-green-100"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
        class="fixed inset-x-0 top-10 mx-auto max-w-md z-50
        rounded-lg shadow-md px-6 py-3 flex items-start gap-2
        text-red-900
        bg-gradient-to-r from-red-100 via-red-50 to-red-100"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <div class="text-sm font-medium">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
        @yield('content')
    </div>
    <footer class="bg-base-200 text-base-content mt-10">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div>
                <h2 class="text-lg font-bold text-primary mb-2">ResellerHub</h2>
                <p class="text-sm text-gray-500">
                    Platform terpercaya untuk reseller mencari produk terbaik dengan harga grosir. Mudah, cepat, dan
                    aman.
                </p>
            </div>

            <!-- Bantuan -->
            <div>
                <h4 class="footer-title">Bantuan</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="/faq" class="link link-hover">FAQ</a></li>
                    <li><a href="/cara-belanja" class="link link-hover">Cara Belanja</a></li>
                    <li><a href="/kontak" class="link link-hover">Kontak Kami</a></li>
                </ul>
            </div>

            <!-- Untuk Reseller -->
            <div>
                <h4 class="footer-title">Untuk Reseller</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="/register" class="link link-hover">Daftar Reseller</a></li>
                    <li><a href="/login" class="link link-hover">Masuk</a></li>
                    <li><a href="/syarat" class="link link-hover">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="footer-title">Customer Service</h4>
                <p class="text-sm">Email: <a href="mailto:support@resellerhub.com"
                        class="link link-hover text-blue-600">support@resellerhub.com</a></p>
                <div class="mt-3 flex space-x-3 text-xl">
                    <a href="#"><i class="fab fa-facebook-f hover:text-blue-600"></i></a>
                    <a href="#"><i class="fab fa-instagram hover:text-pink-500"></i></a>
                    <a href="#"><i class="fab fa-whatsapp hover:text-green-500"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-300 text-center py-4 text-sm text-gray-500">
            &copy; {{ date('Y') }} ResellerHub. All rights reserved.
        </div>
    </footer>


</body>
{{-- <script>
  // Blok klik kanan
  document.addEventListener('contextmenu', event => event.preventDefault());

  // Blok F12, Ctrl+Shift+I, Ctrl+U
  document.addEventListener('keydown', function(e) {
    if (
      e.key === 'F12' ||
      (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) ||
      (e.ctrlKey && e.key === 'U')
    ) {
      e.preventDefault();
    }
  });
</script> --}}

</html>
