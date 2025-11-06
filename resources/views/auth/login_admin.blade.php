<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login ALADZAN CORPORA Sebagai admin</title>
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
            <h2 class="text-xl font-semibold text-gray-800 text-center">Login</h2>
        </div>

        <form x-data="{ loading: false }" @submit="loading = true" action="{{ route('login.admin.post') }}" method="POST"
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
                <a href="{{ route('password.request', ['ir' => 0]) }}" class="text-blue-600 hover:underline">
                    Lupa kata sandi?
                </a>
            </div>

            <div class="my-3">
    {!! NoCaptcha::display() !!}
    @error('g-recaptcha-response')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Login
            </button>
            {!! NoCaptcha::renderJs() !!}
        </form>



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
