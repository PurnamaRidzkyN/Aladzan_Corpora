<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lupa Kata Sandi - ALADZAN CORPORA</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo1.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/logo1.png') }}">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm">
        <div class="flex flex-col items-center mb-4">
            <img src="{{ asset('storage/logo2.png') }}" alt="ALADZAN CORPORA Logo"
                class="w-48 h-auto object-contain mb-2">
            <h2 class="text-xl font-semibold text-gray-800 text-center">Lupa Kata Sandi</h2>

        </div>
        @if (session('status'))
            <div class="mb-4 px-4 py-3 rounded bg-blue-100 border border-blue-300 text-blue-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" x-data="{ loading: false }" @submit="loading = true" method="POST"
            class="space-y-4">
            @csrf
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600">
                    {{ $errors->first('email') }}
                </div>
            @endif
            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                <input type="hidden" name="ir" value="{{ $ir ?? false }}">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Kirim Permintaan Reset Password
            </button>
        </form>


        <!-- Kembali -->
        <div class="mt-4 text-center">
            <a href="{{ route('login.reseller') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
        </div>
    </div>

</body>

</html>
