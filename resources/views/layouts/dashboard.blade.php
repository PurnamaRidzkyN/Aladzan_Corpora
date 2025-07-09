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
  <!-- Sidebar tetap fixed -->
  <aside class="w-64 bg-white border-r border-gray-200 p-6 fixed inset-y-0 left-0 z-40">
    @include('layouts.sidebar_dashboard')
  </aside>

  <!-- Konten utama pakai padding kiri supaya tidak ketiban sidebar -->
  <div class="pl-64">
    <!-- Navbar -->
    @include('layouts.navbar_dashboard')

    <!-- Konten -->
    <main class="p-6">
      @yield('content')
    </main>
  </div>
</body>


</html>
