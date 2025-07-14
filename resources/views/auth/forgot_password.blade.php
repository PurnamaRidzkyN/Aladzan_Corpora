<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login ResellerShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login ke ResellerShop</h2>

        <form action="{{ route('password.email') }}"  x-data="{ loading: false }" @submit="loading = true" method="POST" class="space-y-4">
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
            <a href="{{ url()->previous() }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
        </div>
    </div>

</body>

</html>
