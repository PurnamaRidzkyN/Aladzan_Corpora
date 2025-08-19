<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - ALADZAN CORPORA</title>
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
            <h2 class="text-xl font-semibold text-gray-800 text-center">Ganti Kata Sandi Anda</h2>

        </div>
        <form action="{{ route('password.reset') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Token dan Email -->
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="block text-sm text-gray-600 mb-1">Password Baru</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Simpan Password Baru
            </button>
        </form>
    </div>

</body>

</html>
