<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard')</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" type="image/png" href="{{ asset('storage/logo1.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/logo1.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>

<body class="bg-sky-50 text-gray-800  min-h-screen font-sans overflow-x-hidden " x-data="{ mobileSidebarOpen: false }">
    <!-- Notifikasi Success -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed inset-x-0 top-10 mx-auto max-w-md 
           rounded-lg shadow-md px-6 py-3 flex items-center space-x-3
           text-green-900
           bg-gradient-to-r from-green-100 via-green-50 to-green-100"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 text-green-600" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed inset-x-0 top-10 mx-auto max-w-md
               rounded-lg shadow-md px-6 py-3 flex items-center space-x-3
               text-red-900
               bg-gradient-to-r from-red-100 via-red-50 to-red-100"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">

            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 text-red-600" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Menampilkan Error Validation -->
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed inset-x-0 top-10 mx-auto max-w-md 
        rounded-lg shadow-md px-6 py-3 flex items-start gap-2
        text-red-900
        bg-gradient-to-r from-red-100 via-red-50 to-red-100"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
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

    <!-- Sidebar tetap fixed -->
    <aside
        class="w-64 bg-white border-r border-gray-200 p-6 fixed inset-y-0 left-0 z-40 transform transition-transform duration-200
                -translate-x-full md:translate-x-0"
        :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>
        @if (Auth::guard('admin')->check())
            @include('layouts.sidebar_dashboard')
        @elseif (Auth::guard('reseller')->check())
            @include('layouts.sidebar_dashboard_reseller')
        @endif
    </aside>
    <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-30 z-30 md:hidden" x-cloak
        x-transition:enter="transition ease-out duration-200" x-transition:leave="transition ease-in duration-150">
    </div>


    <div class="md:pl-64 flex flex-col">
        {{-- TOPBAR --}}
        <header class="bg-white/40 backdrop-blur-sm shadow-sm p-2 flex items-center justify-between md:hidden">
            {{-- TOGGLE BUTTON --}}
            <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

        </header>
        @include('layouts.navbar_dashboard', [
            'title' => $title ?? '',
            'breadcrumb' => $breadcrumb ?? [],
        ])
        {{-- CONTENT --}}
        <main class="p-6">
            @yield('content')

        </main>
        @include('layouts.footer')
    </div>

</body>


</html>
