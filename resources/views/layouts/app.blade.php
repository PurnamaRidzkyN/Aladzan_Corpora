<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'ALADZAN CORPORA')</title>
    <meta name="description" content="@yield('description', 'ALADZAN CORPORA: Platform reseller terpercaya.')">
    @yield('head_extra')
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/logo1.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/logo1.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/logo1.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/logo1.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            {{-- Logo --}}
            <a href="/" class="flex-shrink-0">
                <img src="{{ asset('storage/logo2.png') }}" alt="ALADZAN CORPORA Logo"
                    class="w-24 sm:w-32 md:w-48 max-w-full h-auto object-contain">
            </a>

            {{-- Search Form --}}
            <form action="/search" method="GET" class="flex-1 min-w-0 flex">
                <input type="text" name="q" placeholder="Cari produk..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-l-xl focus:ring-2 focus:ring-blue-300 outline-none w-full" />
                <button type="submit"
                    class="flex-none px-4 py-2 bg-blue-600 text-white rounded-r-xl hover:bg-blue-700">
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
            @if (auth()->check())
                <div class="hidden md:flex items-center space-x-4">
                    {{-- Ikon notifikasi --}}

                    @php
                        $user = auth('reseller')->user();
                        $unreadCount = $user?->unreadNotifications()->count() ?? 0;
                    @endphp
                    <a href="{{ route('reseller.notifications') }}"
                        class="group relative flex items-center justify-center text-gray-700 hover:text-blue-600">
                        <i class="fa-solid fa-bell"></i>
                        @if ($unreadCount > 0)
                            <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full text-[10px]">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>
            @endif
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
                            <a href="{{ route('dashboard.admin') }}"
                                class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">
                                    Logout
                                </button>
                            </form>
                        @elseif(auth()->check())
                            <div class="px-3 py-2 text-gray-900 font-semibold truncate">{{ auth()->user()->name }}
                            </div>
                            <a href="/profil" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Profil
                                Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">
                                    Logout
                                </button>
                            </form>
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
            <a href="/"
                class="relative flex flex-col items-center justify-center
            {{ request()->is('/') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">
                <svg class="w-5 h-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2 7-7 7 7 2 2" />
                </svg>
                <span>Home</span>
            </a>

            {{-- Wishlist --}}
            <a href="{{ route('favorite') }}"
                class="relative flex flex-col items-center justify-center
            {{ request()->is('favorite') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                </svg>
                <span>Favorit</span>
            </a>

            {{-- Notifikasi --}}
            @if (auth()->check())
                <a href="{{ route('reseller.notifications') }}"
                    class="relative flex flex-col items-center justify-center
                {{ request()->is('reseller/notifications') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">
                    <i class="fa-solid fa-bell text-[20px] mb-1"></i>
                    <span>Notifikasi</span>
                    @if ($unreadCount > 0)
                        <span
                            class="absolute -top-1 right-2 text-[10px] bg-red-500 text-white px-2 py-0.5 rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            @endif

            {{-- Cart --}}
            <a href="/cart"
                class="relative flex flex-col items-center justify-center
            {{ request()->is('cart') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">
                <i class="fas fa-shopping-cart text-[20px] mb-1"></i>
                <span>Cart</span>
            </a>

            {{-- Profil / Login --}}
            @if (Auth::guard('admin')->check())
                <a href="/admin/dashboard"
                    class="relative flex flex-col items-center justify-center
                {{ request()->is('admin/dashboard') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">
                    <svg class="w-5 h-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A4 4 0 0112 16a4 4 0 016.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            @elseif(auth()->check())
                <a href="/profil"
                    class="relative flex flex-col items-center justify-center
                {{ request()->is('profil') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">
                    <img src="{{ cloudinary_url(auth()->user()->pfp_path) }}"
                        class="h-5 w-5 mb-1 rounded-full object-cover" alt="Profil">
                    <span>Profil</span>
                </a>
            @else
                <a href="/login"
                    class="relative flex flex-col items-center justify-center
                {{ request()->is('login') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">
                    <svg class="w-5 h-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
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
    <div class=" p-10">
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
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="mx-auto max-w-md mb-4  
    rounded-lg shadow-md px-6 py-3 flex items-center space-x-3
    text-red-900 bg-gradient-to-r from-red-100 via-red-50 to-red-100"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 text-red-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="fixed inset-x-0 top-10 mx-auto max-w-md z-50
        rounded-lg shadow-md px-6 py-3 flex items-start gap-2
        text-red-900
        bg-gradient-to-r from-red-100 via-red-50 to-red-100"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
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
    <footer class="bg-base-200 text-base-content mt-10 ">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div>
                <h2 class="text-lg font-bold text-primary mb-2">ResellerHub</h2>
                <p class="text-sm text-gray-500">
                    Platform terpercaya untuk reseller mencari produk terbaik dengan harga grosir. Mudah, cepat, dan
                    aman.
                </p>
            </div>

            <!-- Informasi -->
            <div>
                <h4 class="footer-title">Informasi</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="/snk" class="link link-hover">Syarat & Ketentuan</a></li>
                    <li><a href="/kebijakan-privasi" class="link link-hover">Kebijakan Privasi</a></li>
                    <li><a href="/disclaimer" class="link link-hover">Disclaimer</a></li>
                </ul>
            </div>

            <!-- Tentang -->
            <div>
                <h4 class="footer-title">Tentang</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="/tentang-kami" class="link link-hover">Tentang Kami</a></li>
                    <li><a href="/faq" class="link link-hover">FAQ</a></li>
                    <li><a href="/kontak" class="link link-hover">Kontak Kami</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="footer-title">Customer Service</h4>
                <a href="/feedback" class="link link-hover text-sm">Kritik dan Saran</a>
                <p class="text-sm">Email: <a href="mailto:y.aladzan.92@gmail.com"
                        class="link link-hover text-blue-600">y.aladzan.92@gmail.com</a></p>
                <div class="mt-3 flex space-x-3 text-xl">
                    <a href="#"><i class="fab fa-facebook-f hover:text-blue-600"></i></a>
                    <a href="#"><i class="fab fa-instagram hover:text-pink-500"></i></a>
                    <a href="#"><i class="fab fa-whatsapp hover:text-green-500"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-300 text-center py-4 text-sm text-gray-500">
            &copy; {{ date('Y') }} Y-Aladzan. All rights reserved.
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
