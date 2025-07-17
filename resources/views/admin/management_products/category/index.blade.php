@extends('layouts.dashboard')
@section('title', 'Kategori Produk')

@section('content')
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
