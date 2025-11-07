@extends('layouts.dashboard')
@section('title', 'Daftar Toko')
@php
    $title = 'Daftar toko';
    $breadcrumb = [['label' => 'Manajemen Produk'], ['label' => 'Daftar Toko']];
@endphp

@section('content')
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
    <section class="w-full lg:px-12 mt-8">
        <div class="card bg-white shadow-md rounded-xl border border-soft">
            <div class="card-body">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-primary">Toko</h2>
                    <label for="modal_toko" class="btn btn-sm  btn-gradient-primary border-none">
                        + Tambah Toko
                    </label>
                </div>

                <!-- Search bar -->
                <div class="form-control w-full mb-4">
                    <input type="text" placeholder="Cari toko..." id="searchInput" class="input input-bordered w-full" />
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm" id="shopTable">
                        <thead class="theadisplay">
                            <tr>
                                <th class="py-3">#</th>
                                <th>Nama Toko</th>
                                <th>Deskripsi</th>
                                <th>Rating</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="shopTableBody">
                            @forelse ($shops as $index => $shop)
                                <tr class="hover:bg-accent-light transition" x-data>
                                    <td class="py-2">{{ $index + 1 }}</td>
                                    <td class="shop-name">{{ $shop->name }}</td>
                                    <td class="max-w-xs truncate max-w-[200px] shop-desc" title="{{ $shop->description }}">
                                        {{ $shop->description }}
                                    </td>
                                    <td><span class="text-yellow-500">⭐</span></td>
                                    <td class="text-center space-x-2">
                                        @if ($shop->trashed())
                                            {{-- Restore hanya super admin --}}
                                            @if (auth('admin')->user()?->is_super_admin)
                                                <form action="{{ route('shops.restore', $shop->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('post')
                                                    <button type="submit"
                                                        class="btn btn-warning btn-xs text-white">Restore</button>
                                                </form>
                                            @endif

                                            {{-- Tombol hapus permanen --}}
                                            <button class="btn btn-error btn-xs text-white"
                                                @click.prevent="
                    $dispatch('open-force-delete-modal', {
                        id: {{ $shop->id }},
                        name: '{{ $shop->name }}',
                        url: '{{ route('shops.forceDelete', $shop->id) }}'
                    })
                ">
                                                Hapus Permanen
                                            </button>
                                        @else
                                            <a href="{{ route('shops.products.index', $shop->id) }}"
                                                class="btn btn-success btn-xs text-white">Lihat</a>

                                            {{-- Tombol hapus (soft delete) --}}
                                            <button class="btn btn-error btn-xs text-white"
                                                @click.prevent="
                    $dispatch('open-delete-modal', {
                        id: {{ $shop->id }},
                        name: '{{ $shop->name }}',
                        url: '{{ route('shops.destroy', $shop->id) }}'
                    })
                ">
                                                Hapus
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">Belum ada toko.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
    {{-- Modal force delete --}}
    <div x-data="{ open: false, name: '', url: '' }"
        x-on:open-force-delete-modal.window="
        open = true; 
        name = $event.detail.name; 
        url = $event.detail.url;
    ">
        <template x-if="open">
            <div class="modal modal-open" role="dialog">
                <div class="modal-box">
                    <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus Permanen</h3>
                    <p class="py-4 text-red-500">Toko <strong x-text="name"></strong> akan dihapus <b>permanen</b> dan
                        tidak bisa dikembalikan!</p>
                    <div class="modal-action">
                        <form :action="url" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-gradient-error">Ya, Hapus Permanen</button>
                        </form>
                        <button type="button" class="btn btn-gradient-neutral" @click="open = false">Batal</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <div x-data="{ open: false, name: '', url: '' }"
        x-on:open-delete-modal.window="
        open = true; 
        name = $event.detail.name; 
        url = $event.detail.url;
    ">
        <template x-if="open">
            <div class="modal modal-open" role="dialog">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                    <p class="py-4">Yakin ingin menghapus toko <strong x-text="name"></strong>?</p>
                    <div class="modal-action">
                        <form :action="url" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-gradient-error">Ya, Hapus</button>
                        </form>
                        <button type="button" class="btn btn-gradient-neutral" @click="open = false">Batal</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <!-- Modal Tambah Toko -->
    <input type="checkbox" id="modal_toko" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box w-full max-w-md">
            <h3 class="font-bold text-lg mb-2 text-primary">Tambah Toko</h3>
            <form action="{{ route('shops.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div class="mb-4 text-center">
                    <img id="preview" src="{{ cloudinary_url('productDefault_nawcx4') }}"
                        class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-blue-300 mb-2"
                        alt="Foto Profil">
                    <input type="file" name="img_path" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200"
                        onchange="previewImage(event)">
                </div>
                <input type="text" name="video_path" placeholder="Video Toko" class="input input-bordered w-full">
                <input type="text" name="name" placeholder="Nama toko" class="input input-bordered w-full"
                    required>
                <textarea name="description" placeholder="Deskripsi toko" rows="4" class="textarea textarea-bordered w-full"
                    required></textarea>
                <input type="text" name="zipcode" placeholder="Kode Pos" class="input input-bordered w-full"
                    required>
                <div class="modal-action">
                    <label for="modal_toko" class="btn  btn-gradient-neutral">Batal</label>
                    <button type="submit" class="btn btn-gradient-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('searchInput');
            const rows = document.querySelectorAll('#shopTableBody tr');

            input.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();

                rows.forEach(row => {
                    const name = row.querySelector('.shop-name')?.textContent.toLowerCase() || '';
                    const desc = row.querySelector('.shop-desc')?.textContent.toLowerCase() || '';

                    if (name.includes(keyword) || desc.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
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
@endsection
