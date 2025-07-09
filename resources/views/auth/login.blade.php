<!DOCTYPE html>
<html lang="id">

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

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
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
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm text-gray-600 mb-1">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 pr-12 border rounded-lg focus:ring focus:ring-blue-300">
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                        <i class="fa fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>


            <div class="flex justify-between items-center text-sm">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2"> Ingat saya
                </label>
                <a href="" class="text-blue-600 hover:underline">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Login
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-4">
            <div class="flex-grow h-px bg-gray-300"></div>
            <span class="mx-3 text-sm text-gray-500">atau</span>
            <div class="flex-grow h-px bg-gray-300"></div>
        </div>

        <!-- Login Google -->
        <a href=""
            class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
            Login / Daftar dengan Gmail
        </a>

        <!-- Daftar -->
        <p class="text-center text-sm mt-4 text-gray-600">
            Belum punya akun? <a href="" class="text-blue-600 hover:underline">Daftar di sini</a>
        </p>

        <!-- Kembali -->
        <div class="mt-4 text-center">
            <a href="{{ url()->previous() }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
        </div>
    </div>

    <!-- Password toggle JS -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

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
