@extends('layouts.dashboard')
@section('title', 'Kategori Produk')

@section('content')
    <!-- Notifikasi Success -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed inset-x-0 top-10 mx-auto max-w-md 
           rounded-lg shadow-md px-6 py-3 flex items-center space-x-3
           text-green-900
           bg-gradient-to-r from-green-100 via-green-50 to-green-100"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 text-green-600" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Menampilkan Error Validation -->
    @if ($errors->any())
        <div class="alert alert-error shadow-lg max-w-sm my-4">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </span>
            </div>
        </div>
    @endif

    <section class="w-full lg:px-12 mt-8">
        <div class="card bg-white shadow-md rounded-xl border border-soft">
            <div class="card-body">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-primary">Kategori Produk</h2>
                    <label for="modal_kategori" class="btn btn-sm text-white btn-gradient-primary border-none">
                        + Tambah Kategori
                    </label>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead class="theadisplay">
                            <tr>
                                <th class="py-3">#</th>
                                <th>Nama Kategori</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $index => $category)
                                <tr class="hover:bg-accent-light transition">
                                    <td class="py-2">{{ $index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td class="text-center space-x-2">

                                        <!-- Tombol Hapus -->
                                        <label for="delete-modal-{{ $category->id }}"
                                            class="btn btn-error btn-xs text-white">Hapus</label>

                                        <!-- Modal Konfirmasi -->
                                        <input type="checkbox" id="delete-modal-{{ $category->id }}" class="modal-toggle" />
                                        <div class="modal" role="dialog">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                                <p class="py-4">Apakah kamu yakin ingin menghapus kategori
                                                    <strong>{{ $category->name }}</strong>?</p>
                                                <div class="modal-action">
                                                    <form method="POST"
                                                        action="{{ route('categories.destroy', $category->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-gradient-error">Ya, Hapus</button>
                                                    </form>
                                                    <label for="delete-modal-{{ $category->id }}"
                                                        class="btn">Batal</label>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500 py-6">
                                        Belum ada kategori.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </section>


    <!-- Modal Tambah Kategori -->
    <input type="checkbox" id="modal_kategori" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box w-full max-w-md">
            <h3 class="font-bold text-lg mb-2 text-primary">Tambah Kategori Produk</h3>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" placeholder="Nama kategori" class="input input-bordered w-full"
                    required>
                <div class="modal-action">
                    <label for="modal_kategori" class="btn">Batal</label>
                    <button type="submit" class="btn btn-gradient-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


@endsection
