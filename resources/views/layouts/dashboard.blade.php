<!DOCTYPE html>
<html lang="id" data-theme="light">
>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>

<body class="bg-gray-50 min-h-screen font-sans overflow-x-hidden">
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
    <!-- Sidebar tetap fixed -->
    <aside class="w-64 bg-white border-r border-gray-200 p-6 fixed inset-y-0 left-0 z-40">
        @include('layouts.sidebar_dashboard')
    </aside>

    <!-- Konten utama pakai padding kiri supaya tidak ketiban sidebar -->
    <div class="pl-64">
        <!-- Navbar -->
        @include('layouts.navbar_dashboard', [
            'title' => $title ?? '',
            'breadcrumb' => $breadcrumb ?? [],
        ])
        <!-- Konten -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>
</body>


</html>
