<aside class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50 flex flex-col rounded-r-2xl border-r border-gray-100">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
        <img src="{{ asset('storage/logo2.png') }}" alt="ALADZAN CORPORA Logo" class="w-48 h-auto object-contain mb-2">
    </div>
    <!-- Scrollable Area -->
    <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">

        <!-- Menu Title -->
        <div class="text-xs text-gray-400 font-bold uppercase px-2">Menu</div>

        <!-- Menu Items -->
        <nav class="flex flex-col space-y-2 text-sm">
            <a href="{{ route('dashboard.reseller') }}"
                class="flex items-center px-4 py-2 rounded-lg font-medium transition
        {{ request()->routeIs('dashboard.reseller') ? 'text-blue-700 bg-blue-50 hover:bg-blue-100' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa-solid fa-tv text-blue-500 w-4"></i>
                <span class="ml-3">Dashboard</span>
            </a>


            <a href="{{ route('reseller.course') }}"
                class="flex items-center px-4 py-2 rounded-lg font-medium transition
        {{ request()->routeIs('reseller.course') ? 'bg-purple-50 text-purple-700' : 'hover:bg-gray-100 text-gray-700' }}">
                <i class="fa-solid fa-graduation-cap text-purple-500 w-4"></i>
                <span class="ml-3">Pembelajaran</span>
            </a>
            <a href="{{ route('communities') }}"
                class="flex items-center px-4 py-2 rounded-lg font-medium transition
        {{ request()->routeIs('communities') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                <i class="fa-solid fa-handshake text-blue-500 w-4"></i>
                <span class="ml-3">List Komunitas</span>
            </a>

        </nav>
    </div>

    <!-- Profil Admin -->
    <div class="border-t px-4 py-3 bg-white hover:bg-gray-50 transition">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ cloudinary_url(auth()->user()->pfp_path) }}" alt="Avatar"
                    class="w-11 h-11 rounded-full border object-cover">

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
                    <li><a href="{{ route('profile') }}" class="text-sm">Lihat Profil</a></li>
                    <li><a href="{{ route('home') }}" class="text-sm">Keluar Dashboard</a></li>
                    <li><a href="{{ route('logout') }}" class="text-sm text-red-500">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

</aside>
