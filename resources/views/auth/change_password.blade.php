@extends($layout)
@section('title', 'Kategori Produk')
@section('content')

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 border border-red-300 px-4 py-3 rounded">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Ubah kata sandi </h2>

            <form method="POST" action="{{ route('change.password.post') }}">
                @csrf
                @method('POST')

                <!-- Password Lama -->
                <div class="mb-4">
                    <label for="current_password" class="block text-sm text-gray-600 mb-1">Password Lama</label>
                    <div class="relative">
                        <input id="current_password" type="password" name="current_password" required
                            class="w-full px-4 py-2 pr-12 border rounded-lg focus:ring focus:ring-blue-300">
                        <button type="button" onclick="togglePassword('current_password', 'toggleCurrent')"
                            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 z-10">
                            <i class="fa fa-eye" id="toggleCurrent"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Baru -->
                <div class="mb-4">
                    <label for="new_password" class="block text-sm text-gray-600 mb-1">Password Baru</label>
                    <div class="relative">
                        <input id="new_password" type="password" name="new_password" required
                            class="w-full px-4 py-2 pr-12 border rounded-lg focus:ring focus:ring-blue-300">
                        <button type="button" onclick="togglePassword('new_password', 'toggleNew')"
                            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 z-10">
                            <i class="fa fa-eye" id="toggleNew"></i>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-6">
                    <label for="new_password_confirmation" class="block text-sm text-gray-600 mb-1">Konfirmasi Password
                        Baru</label>
                    <div class="relative">
                        <input id="new_password_confirmation" type="password" name="new_password_confirmation" required
                            class="w-full px-4 py-2 pr-12 border rounded-lg focus:ring focus:ring-blue-300">
                        <button type="button" onclick="togglePassword('new_password_confirmation', 'toggleConfirm')"
                            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 z-10">
                            <i class="fa fa-eye" id="toggleConfirm"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </form>
            <div class="mt-4 text-center">
                <a href="{{ url()->previous() }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
            </div>
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
    @endsection
