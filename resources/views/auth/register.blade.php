<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Lengkapi Data Anda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
 

    <div class="bg-white p-6 rounded-xl shadow-md w-full max-w-md">
         @if ($errors->any())
    <div class="mb-4 text-sm text-red-600">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <h2 class="text-2xl font-bold mb-6 text-center">Lengkapi Data Anda</h2>

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4" >
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-sm mb-1">Nama</label>
                <input type="text" name="name"
                    value="{{ old('name', optional($user)->name) }}"
                    {{ optional($user)->name ? 'readonly' : '' }}
                    class="input input-bordered w-full {{ optional($user)->name ? 'bg-gray-100 text-gray-500' : '' }}">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email"
                    value="{{ old('email', optional($user)->email) }}"
                    {{ optional($user)->email? 'readonly' : '' }}
                    class="input input-bordered w-full {{ optional($user)->email ? 'bg-gray-100 text-gray-500' : '' }}">
            </div>

            {{-- Password (hanya jika bukan dari Google) --}}
            @if (!optional($user)->google_id) {{-- ganti sesuai field penanda akun Google --}}
<!-- Password -->
<div class="mb-4">
    <label for="password" class="block text-sm text-gray-600 mb-1">Password</label>
    <div class="relative">
        <input id="password" type="password" name="password" required
            class="w-full px-4 py-2 pr-12 border rounded-lg focus:ring focus:ring-blue-300">
        <button type="button" onclick="togglePassword('password', 'toggleIcon')"
            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 z-10">
            <i class="fa fa-eye" id="toggleIcon"></i>
        </button>
    </div>
</div>

<!-- Konfirmasi Password -->
<div class="mb-4">
    <label for="password_confirmation" class="block text-sm text-gray-600 mb-1">Konfirmasi Password</label>
    <div class="relative">
        <input id="password_confirmation" type="password" name="password_confirmation" required
            class="w-full px-4 py-2 pr-12 border rounded-lg focus:ring focus:ring-blue-300">
        <button type="button" onclick="togglePassword('password_confirmation', 'toggleConfirmIcon')"
            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 z-10">
            <i class="fa fa-eye" id="toggleConfirmIcon"></i>
        </button>
    </div>
</div>

                @else
                <input type="hidden" name="google_id" value="{{ optional($user)->google_id }}">
            @endif

            {{-- Nomor HP --}}
            <div>
                <label class="block text-sm mb-1">Nomor HP</label>
                <input type="text" name="phone"
                    required
                    class="input input-bordered w-full">
            </div>

            <button type="submit" class="btn btn-primary w-full">Simpan dan Lanjut</button>
        </form>
         <div class="mt-4 text-center">
            <a href="{{ route('login.reseller') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali ke login</a>
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
