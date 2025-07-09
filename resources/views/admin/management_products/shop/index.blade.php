@extends('layouts.dashboard')
@section('title', 'Kategori Produk')

@section('content')
<section class="w-full lg:px-12 mt-8">
    <div class="card bg-white shadow-md rounded-xl border border-soft">
        <div class="card-body">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-primary">Daftar Toko</h2>
                <label for="modal_toko" class="btn btn-sm text-white btn-gradient-primary border-none">
                    + Tambah Toko
                </label>
            </div>

            <!-- Search bar -->
            <div class="form-control w-full mb-4">
                <input type="text" placeholder="Cari toko..." class="input input-bordered w-full" />
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table w-full text-sm">
                    <thead class="theadisplay">
                        <tr>
                            <th class="py-3">#</th>
                            <th>Nama Toko</th>
                            <th>Deskripsi</th>
                            <th>Rating</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shops as $index => $shop)
                        <tr class="hover:bg-accent-light transition">
                            <td class="py-2">{{ $index + 1 }}</td>
                            <td>{{ $shop->name }}</td>
                            <td class="max-w-xs  truncate max-w-[200px]" title="{{ $shop->description }}">{{ $shop->description }}</td>
                            <td>
                                <span class="text-yellow-500">⭐</span>
                                {{-- {{ number_format($shop->rating ?? 0, 1) }} --}}
                            </td>
                            <td class="text-center space-x-2">
                                <a href="{{ route('shops.show', $shop->id) }}" class="btn btn-success btn-xs text-white">Lihat</a>
                                <label for="delete-store-{{ $shop->id }}" class="btn btn-error btn-xs text-white">Hapus</label>

                                <!-- Modal konfirmasi -->
                                <input type="checkbox" id="delete-store-{{ $shop->id }}" class="modal-toggle" />
                                <div class="modal" role="dialog">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                        <p class="py-4">Yakin ingin menghapus toko <strong>{{ $shop->name }}</strong>?</p>
                                        <div class="modal-action">
                                            <form action="{{ route('shops.destroy', $shop->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-gradient-error">Ya, Hapus</button>
                                            </form>
                                            <label for="delete-store-{{ $shop->id }}" class="btn">Batal</label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada toko yang terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>

<!-- Modal Tambah Toko -->
<input type="checkbox" id="modal_toko" class="modal-toggle" />
<div class="modal">
    <div class="modal-box w-full max-w-md">
        <h3 class="font-bold text-lg mb-2 text-primary">Tambah Toko</h3>
        <form action="{{ route('shops.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Nama toko" class="input input-bordered w-full" required>
            <textarea name="description" placeholder="Deskripsi toko" rows="4" class="textarea textarea-bordered w-full" required></textarea>
            <div class="modal-action">
                <label for="modal_toko" class="btn">Batal</label>
                <button type="submit" class="btn btn-gradient-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
