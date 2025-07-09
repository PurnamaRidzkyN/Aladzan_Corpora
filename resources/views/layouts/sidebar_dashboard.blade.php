<aside class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50 flex flex-col rounded-r-2xl border-r border-gray-100">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
        <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Logo" class="w-9 h-9 rounded">
        <span class="font-semibold text-gray-800 text-lg tracking-tight">Reseller</span>
    </div>

    <!-- Scrollable Area -->
    <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">

        <!-- Menu Title -->
        <div class="text-xs text-gray-400 font-bold uppercase px-2">Menu</div>

        <!-- Menu Items -->
        <nav class="flex flex-col space-y-2 text-sm">
            <a href="#"
                class="flex items-center px-4 py-2 rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition font-medium">
                <i class="fas fa-tv w-4 text-blue-500"></i>
                <span class="ml-3">Dashboard</span>
            </a>

            <!-- Dropdown Menu -->
            <div x-data="{ open: true }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700 font-medium transition">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-box w-4 text-orange-500"></i>
                        <span>Manajemen Produk</span>
                    </div>
                    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-cloak class="ml-10 mt-1 flex flex-col space-y-1 border-l border-gray-200 pl-3">
                    <a href="{{ route('categories.index') }}"
                        class="text-gray-600 hover:text-blue-600 py-1 transition">📁 Kategori Produk</a>
                    <a href="{{ route('shops.index') }}" class="text-gray-600 hover:text-blue-600 py-1 transition">📦 Daftar Produk</a>
                </div>
            </div>

            <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 transition text-gray-700">
                <i class="fas fa-shopping-cart w-4 text-green-500"></i>
                <span class="ml-3">Pesanan</span>
            </a>

            <a href="{{ route('resellers.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 transition text-gray-700">
                <i class="fas fa-users w-4 text-cyan-500"></i>
                <span class="ml-3">Pelanggan</span>
            </a>
        </nav>

        <!-- Notifikasi -->
        <div class="text-xs text-gray-400 font-bold uppercase mt-6 px-2">Lainnya</div>
        <div class="flex items-center justify-between px-4 py-2 hover:bg-gray-100 rounded-lg cursor-pointer">
            <div class="flex items-center gap-3 text-sm text-gray-700">
                <i class="fas fa-bell text-yellow-500 w-4"></i>
                <span>Notifikasi</span>
            </div>
            <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full text-[10px]">3</span>
        </div>
    </div>

    <!-- Profil Admin -->
    <div class="border-t px-4 py-3 bg-white hover:bg-gray-50 transition">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="https://i.pravatar.cc/48?img=12" alt="Avatar" class="w-11 h-11 rounded-full border">
                <div>
                    <p class="font-semibold text-sm text-gray-900">Admin Reseller</p>
                    <p class="text-xs text-gray-500">@admin@email.com</p>
                </div>
            </div>

            <!-- Dropdown (optional) -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-sm btn-circle">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </div>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 mt-3">
                    <li><a href="#" class="text-sm">Lihat Profil</a></li>
                    <li><a href="#" class="text-sm">Keluar Dashboard</a></li>
                    <li><a href="#" class="text-sm text-red-500">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

</aside>
