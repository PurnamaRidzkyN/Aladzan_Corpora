@extends('layouts.app')

@section('content')
    <!-- Tambahkan Font Awesome jika belum ada di layout -->

    <div x-data="{ open: false }">
        <div class="max-w-xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="p-8 text-center">
                <!-- Foto Profil -->
                <div class="flex justify-center">
                    <img src="{{ cloudinary_url(auth()->user()->pfp_path) }}" alt="Foto Profil"
                        class="w-24 h-24 rounded-full border-4 border-blue-200 shadow-lg object-cover">
                </div>

                <!-- Nama & Info -->
                <h2 class="mt-4 text-2xl font-bold text-blue-900">
                    {{ Auth::user()->name }}
                </h2>
                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                <p class="text-gray-500 mb-6">{{ Auth::user()->phone ?? 'Nomor belum diatur' }}</p>

                <!-- Menu Navigasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left">
                    <a href="{{ route('order.history') }}"
                        class="flex items-center p-4 rounded-xl hover:bg-blue-100 transition">
                        <i class="fas fa-box text-blue-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-blue-900">Riwayat Pembelian</div>
                            <div class="text-sm text-gray-500">Lihat transaksi kamu</div>
                        </div>
                    </a>
                    <a href="{{ route('address.index') }}"
                        class="flex items-center p-4 rounded-xl hover:bg-blue-100 transition">
                        <i class="fas fa-map-marker-alt text-blue-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-blue-900">Daftar Alamat</div>
                            <div class="text-sm text-gray-500">Atur alamat kirim</div>
                        </div>
                    </a>
                    <a href="{{ route('dashboard.reseller') }}"
                        class="flex items-center p-4 rounded-xl hover:bg-blue-100 transition">
                        <i class="fas fa-chart-line text-blue-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-blue-900">Masuk Dashboard</div>
                            <div class="text-sm text-gray-500">Masuk ke dashboard</div>
                        </div>
                    </a>
                    <a href="#" @click="open = true"
                        class="flex items-center p-4 rounded-xl hover:bg-blue-100 transition">
                        <i class="fas fa-user-cog text-blue-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-blue-900">Edit Akun</div>
                            <div class="text-sm text-gray-500">Ubah info akun</div>
                        </div>
                    </a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center p-4 rounded-xl hover:bg-red-100 transition">
                        <i class="fas fa-sign-out-alt text-red-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-red-600">Logout</div>
                            <div class="text-sm text-gray-500">Keluar dari akun ini</div>
                        </div>
                    </a>

                    <!-- Form logout -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Akun -->
        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
            <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg relative">
                <!-- Close Button -->
                <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                    &times;
                </button>

                <!-- Form Edit -->
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('post')

                    <!-- Foto -->
                    <div class="mb-4 text-center">
                        <img id="preview" src="{{ cloudinary_url(auth()->user()->pfp_path) }}"
                            class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-blue-300 mb-2"
                            alt="Foto Profil">
                        <input type="file" name="pfp" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200"
                            onchange="previewImage(event)">
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
    @if (session('verification'))
        <div x-data="{ open: true }" x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-200 w-[90%] max-w-md p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-100 text-green-600 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2l4 -4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Konfirmasi Perubahan Email</h3>
                </div>
                <p class="text-gray-600 text-sm mb-6">
                    {{ session('verification') }}
                </p>
                <div class="text-right">
                    <button @click="open = false"
                        class="btn btn-sm btn-primary px-4 rounded-lg shadow hover:shadow-md transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection
<script>
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();

        reader.onload = function() {
            const preview = document.getElementById('preview');
            preview.src = reader.result;
        };

        reader.readAsDataURL(input.files[0]);
    }
</script>
