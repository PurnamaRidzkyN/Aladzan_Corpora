<!DOCTYPE html>
<html lang="id" class="bg-gray-100" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register ALADZAN CORPORA</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo1.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/logo1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md relative">
        <!-- Judul Form -->
        <div class="text-center mb-8">
            <!-- Logo -->
            <img src="{{ asset('storage/logo1.png') }}" alt="ALADZAN CORPORA Logo"
                class="mx-auto w-24 h-24 object-contain mb-4">

            <!-- Judul -->
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">Buat Akun</h1>

            <!-- Deskripsi -->
            <p class="text-gray-500 text-sm sm:text-base">Lengkapi data Anda untuk mulai menggunakan ResellerShop</p>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-50 p-3 rounded">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
            @csrf

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', optional($user)->name) }}"
                    {{ optional($user)->name ? 'readonly' : '' }}
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:outline-none {{ optional($user)->name ? 'bg-gray-100 text-gray-500' : '' }}">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', optional($user)->email) }}"
                    {{ optional($user)->email ? 'readonly' : '' }}
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:outline-none {{ optional($user)->email ? 'bg-gray-100 text-gray-500' : '' }}">
            </div>

            <!-- Password -->
            @if (!optional($user)->google_id)
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:outline-none pr-12">
                        <button type="button" onclick="togglePassword('password','toggleIcon')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                            <i class="fa fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="relative mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:outline-none pr-12">
                        <button type="button" onclick="togglePassword('password_confirmation','toggleConfirmIcon')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                            <i class="fa fa-eye" id="toggleConfirmIcon"></i>
                        </button>
                    </div>
                </div>
            @else
                <input type="hidden" name="google_id" value="{{ optional($user)->google_id }}">
            @endif

            <!-- Nomor HP -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                <input type="text" name="phone" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition">
                Daftar Sekarang
            </button>
        </form>

        <!-- Link login -->
        <div class="mt-6 text-center text-gray-500 text-sm">
            Sudah punya akun? <a href="{{ route('login.reseller') }}" class="text-blue-600 hover:underline">Masuk</a>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>
