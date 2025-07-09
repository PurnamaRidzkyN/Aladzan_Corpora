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
      .scrollbar-hide::-webkit-scrollbar { display: none; }
      .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    
    </style>
  </head>

  <body class="bg-gray-50 font-sans " x-data="{ open: false, openUser: false, openKategori: false } ">
    <!-- 🔼 Navbar Atas -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
        <!-- Logo -->
        <div class="text-xl font-bold text-blue-600">Reseller<span class="text-gray-800">Shop</span></div>

        <!-- Search -->
        <div class="flex-1">
          <input type="text" placeholder="Cari produk..." class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-300 outline-none" />
        </div>

        <!-- Menu -->
        <div class="hidden md:flex items-center gap-4">
          <!-- Dropdown Kategori -->
          <div class="relative">
            <button @click="open = !open" class="text-sm text-gray-600 hover:text-blue-600">Kategori ▾</button>
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg p-3 z-10">
              <label class="flex items-center text-sm text-gray-700 mb-2">
                <input type="checkbox" class="form-checkbox text-blue-600 mr-2"> Pakaian
              </label>
              <label class="flex items-center text-sm text-gray-700 mb-2">
                <input type="checkbox" class="form-checkbox text-blue-600 mr-2"> Makanan
              </label>
              <label class="flex items-center text-sm text-gray-700 mb-2">
                <input type="checkbox" class="form-checkbox text-blue-600 mr-2"> Kecantikan
              </label>
              <label class="flex items-center text-sm text-gray-700">
                <input type="checkbox" class="form-checkbox text-blue-600 mr-2"> Lainnya
              </label>
            </div>
          </div>

          <!-- Cart -->
          <a href="/cart" class="relative text-gray-700 hover:text-blue-600 text-xl">
            🛒
            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">0</span>
          </a>

          <!-- Profil -->
          <div class="relative">
            <button @click="openUser = !openUser" class="w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 16a4 4 0 016.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </button>
            <div x-show="openUser" @click.away="openUser = false" x-cloak class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg p-3 z-10">
              <a href="/login" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Login</a>
              <a href="/register" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Daftar</a>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- 🔽 Mobile Bottom Nav -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t z-50 shadow">
      <div class="flex justify-around items-center h-16 text-sm text-gray-700">
        <a href="/" class="flex flex-col items-center hover:text-blue-600">
          <svg class="h-5 w-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2 7-7 7 7 2 2" />
          </svg>
          Home
        </a>

        <button @click="openKategori = !openKategori" class="flex flex-col items-center hover:text-blue-600">
          <svg class="h-5 w-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
          </svg>
          Kategori
        </button>

        <a href="/cart" class="relative flex flex-col items-center hover:text-blue-600">
          <svg class="h-5 w-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2 9h14l-2-9" />
          </svg>
          Cart
          <span class="absolute top-0 right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">2</span>
        </a>

        <a href="/login" class="flex flex-col items-center hover:text-blue-600">
          <svg class="h-5 w-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 16a4 4 0 016.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Profil
        </a>
      </div>
    </div>

    <!-- ✅ Mobile Kategori Checklist -->
    <div 
      x-show="openKategori" 
      x-cloak 
      @click.away="openKategori = false"
      class="fixed bottom-16 left-0 right-0 mx-auto max-w-md bg-white border border-gray-300 rounded-t-xl shadow-lg p-4 z-40"
    >
      <h3 class="font-semibold text-gray-700 mb-2">Pilih Kategori</h3>
      <label class="flex items-center text-sm text-gray-800 mb-2">
        <input type="checkbox" class="form-checkbox text-blue-600 mr-2"> Pakaian
      </label>
      <label class="flex items-center text-sm text-gray-800 mb-2">
        <input type="checkbox" class="form-checkbox text-blue-600 mr-2"> Makanan
      </label>
      <label class="flex items-center text-sm text-gray-800 mb-2">
        <input type="checkbox" class="form-checkbox text-blue-600 mr-2"> Kecantikan
      </label>
      <label class="flex items-center text-sm text-gray-800">
        <input type="checkbox" class="form-checkbox text-blue-600 mr-2"> Lainnya
      </label>
    </div>
    

    {{-- Konten halaman --}}
    <div class="pb-24">
    @yield('content')
    </div>
  </body>
</html>
