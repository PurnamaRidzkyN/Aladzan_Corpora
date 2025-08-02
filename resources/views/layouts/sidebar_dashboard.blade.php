<aside class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50 flex flex-col rounded-r-2xl border-r border-gray-100">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
        <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Logo" class="w-9 h-9 rounded">
        <span class="font-semibold text-gray-800 text-lg tracking-tight">Reseller</span>
    </div>

    <!-- Menu -->
    <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
        <div class="text-xs text-gray-400 font-bold uppercase px-2">Menu</div>

        <nav class="flex flex-col space-y-2 text-sm">
            <!-- Dashboard -->
            <a href="{{ route('dashboard.admin') }}"
                class="flex items-center px-4 py-2 rounded-lg font-medium transition
        {{ request()->routeIs('dashboard.admin') ? 'text-blue-700 bg-blue-50 hover:bg-blue-100' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa-solid fa-tv text-blue-500 w-4"></i>
                <span class="ml-3">Dashboard</span>
            </a>

            <!-- Dropdown Manajemen Produk -->
            <div x-data="{ open: {{ request()->routeIs('categories.*') || request()->routeIs('shops.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 rounded-lg font-medium transition
            {{ request()->routeIs('categories.*') || request()->routeIs('shops.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-box text-orange-500 w-4"></i>
                        <span>Manajemen Produk</span>
                    </div>
                    <i :class="{ 'rotate-180': open }" class="fa-solid fa-chevron-down w-4 transition-transform"></i>
                </button>
                <div x-show="open" x-cloak class="ml-10 mt-1 flex flex-col space-y-1 border-l border-gray-200 pl-3">
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center px-4 py-2 rounded-lg font-medium transition
                {{ request()->routeIs('categories.*') ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i class="fa-solid fa-folder text-yellow-500 w-4"></i>
                        <span class="ml-3">Kategori Produk</span>
                    </a>
                    <a href="{{ route('shops.index') }}"
                        class="flex items-center px-4 py-2 rounded-lg font-medium transition
                {{ request()->routeIs('shops.*') ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i class="fa-solid fa-boxes-stacked text-orange-600 w-4"></i>
                        <span class="ml-3">Daftar Produk</span>
                    </a>
                </div>
            </div>


            <!-- Dropdown Pesanan -->
            <div x-data="{ open: {{ request()->routeIs('orders.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 rounded-lg font-medium transition
            {{ request()->routeIs('orders.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clipboard-list text-green-600 w-4"></i>
                        <span>Pesanan</span>
                    </div>
                    <i :class="{ 'rotate-180': open }" class="fa-solid fa-chevron-down w-4 transition-transform"></i>
                </button>
                <div x-show="open" x-cloak class="ml-10 mt-1 flex flex-col space-y-1 border-l border-gray-200 pl-3">
                    <a href="{{ route('orders.current') }}"
                        class="flex items-center px-4 py-2 rounded-lg font-medium transition
                {{ request()->routeIs('orders.current') ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i class="fa-solid fa-cart-shopping text-green-500 w-4"></i>
                        <span class="ml-3">Pesanan Masuk</span>
                    </a>
                    <a href="{{ route('orders.history') }}"
                        class="flex items-center px-4 py-2 rounded-lg font-medium transition
                {{ request()->routeIs('orders.history') ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i class="fa-solid fa-clock-rotate-left text-gray-500 w-4"></i>
                        <span class="ml-3">Riwayat Pesanan</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('discount.index') }}"
                class="flex items-center px-4 py-2 rounded-lg font-medium transition
        {{ request()->routeIs('discount.index') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                <i class="fa-solid fa-percent text-purple-500 w-4"></i>
                <span class="ml-3">Manajemen Diskon Akun</span>
            </a>

            <!-- Lainnya -->
            <a href="{{ route('group.course') }}"
                class="flex items-center px-4 py-2 rounded-lg font-medium transition
        {{ request()->routeIs('group.course') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                <i class="fa-solid fa-graduation-cap text-purple-500 w-4"></i>
                <span class="ml-3">Pembelajaran</span>
            </a>

            <a href="{{ route('admins.index') }}"
                class="flex items-center px-4 py-2 rounded-lg font-medium transition
        {{ request()->routeIs('admins.index') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                <i class="fa-solid fa-users-gear text-green-500 w-4"></i>
                <span class="ml-3">List Admin</span>
            </a>


            <a href="{{ route('reseller.index') }}"
                class="flex items-center px-4 py-2 rounded-lg font-medium transition
        {{ request()->routeIs('reseller.index') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                <i class="fa-solid fa-users text-cyan-500 w-4"></i>
                <span class="ml-3">List Reseller</span>
            </a>


        </nav>

        <!-- Notifikasi -->
        <div class="text-xs text-gray-400 font-bold uppercase mt-6 px-2">Lainnya</div>
        <div class="flex items-center justify-between px-4 py-2 hover:bg-gray-100 rounded-lg cursor-pointer">
            <div class="flex items-center gap-3 text-sm text-gray-700">
                <i class="fa-solid fa-bell text-yellow-500 w-4"></i>
                <span>Notifikasi</span>
            </div>
            <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full text-[10px]">3</span>
        </div>
    </div>

    <div class="border-t px-4 py-3 bg-white hover:bg-gray-50 transition">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    @if (Auth::guard('admin')->check())
                        <p class="font-semibold text-sm text-gray-900">{{ Auth::guard('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::guard('admin')->user()->email }}</p>
                    @elseif (Auth::guard('reseller')->check())
                        <p class="font-semibold text-sm text-gray-900">{{ Auth::guard('reseller')->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::guard('reseller')->user()->email }}</p>
                    @else
                        <p class="text-sm text-red-600">Tidak ada user yang login</p>
                    @endif
                </div>
            </div>

            <!-- Dropdown (optional) -->
            <div class="dropdown dropdown-top">
                <div tabindex="0" role="button" class="btn btn-ghost btn-sm btn-circle">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </div>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 mt-3">
                    <li><a href="{{ route('change.password') }}" class="text-sm">Ganti Kata Sandi</a></li>
                    <li><a href="{{ route('home') }}" class="text-sm">Keluar Dashboard</a></li>
                    <li><a href={{ route('logout') }} class="text-sm text-red-500">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</aside>
