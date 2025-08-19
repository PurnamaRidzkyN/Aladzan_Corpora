@extends('layouts.dashboard')

@section('title', 'Grup Komunitas')
@php
    $title = 'Grup Komunitas';
    $breadcrumb = [ ['label' => 'Grup Komunitas']];
@endphp

@section('content')
    <div class="max-w-6xl mx-auto" x-data="{
        search: '',
        groups: @js($community),
        filteredGroups() {
            return this.groups.filter(g =>
                g.name.toLowerCase().includes(this.search.toLowerCase()) ||
                g.description.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }">
        <!-- Search & Manage -->
        <div class="flex justify-center gap-4 mb-8">
            <input type="text" x-model="search" placeholder="Cari grup komunitas..."
                class="input input-bordered w-full max-w-md shadow-sm focus:shadow-lg transition-all" />
            @if (auth('admin')->check())
                <button class="btn btn-primary" onclick="groupModal.showModal()">Kelola Grup</button>
            @endif
        </div>

        <!-- Group Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="group in filteredGroups()" :key="group.id">
                <div class="card bg-white shadow-lg hover:shadow-xl transition-all border border-gray-100 rounded-2xl">
                    <div class="card-body">
                        <h2 class="card-title text-primary font-semibold" x-text="group.name"></h2>
                        <p class="text-gray-600 text-sm leading-relaxed" x-text="group.description"></p>
                        <div class="card-actions mt-4">
                            <a :href="group.link" target="_blank" class="btn btn-gradient-primary btn-sm w-full">
                                Gabung Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filteredGroups().length === 0" class="col-span-full text-center p-10">
               <div
                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-9 4h10m-10 0a2 2 0 110-4h10a2 2 0 110 4m-10 0V20" />
                    </svg>
                    <p class="text-sm text-gray-500">Tidak ada grup yang cocok dengan pencarian.</p>
                </div>
            </div>
        </div>


        <!-- Modal Kelola Grup -->
        <dialog id="groupModal" class="modal">
            <div class="modal-box max-w-lg">
                <h3 class="font-bold text-lg mb-4">Kelola Grup</h3>

                <!-- Form Tambah -->
                <form action="{{ route('communities.store') }}" method="POST" class="space-y-3 mb-6">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Grup" class="input input-bordered w-full"
                        required>
                    <input type="text" name="description" placeholder="Deskripsi" class="input input-bordered w-full"
                        required>
                    <input type="url" name="link" placeholder="Link (https://...)"
                        class="input input-bordered w-full" required>
                    <button type="submit" class="btn btn-gradient-primary w-full">Tambah Grup</button>
                </form>

                <!-- List Grup -->
                <!-- List Grup -->
                <div class="max-h-60 overflow-y-auto space-y-2">
                    @forelse($community as $group)
                        <div class="flex items-center justify-between p-2 border rounded-lg">
                            <div>
                                <h4 class="font-semibold">{{ $group->name }}</h4>
                                <p class="text-xs text-gray-500 truncate w-48">{{ $group->description }}</p>
                            </div>

                            <!-- Tombol buka modal hapus -->
                            <label for="delete-group-{{ $group->id }}" class="btn btn-error btn-xs text-white">Hapus</label>

                            <!-- Modal konfirmasi hapus -->
                            <input type="checkbox" id="delete-group-{{ $group->id }}" class="modal-toggle" />
                            <div class="modal" role="dialog">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                    <p class="py-4">
                                        Yakin ingin menghapus grup <strong>{{ $group->name }}</strong>?
                                    </p>
                                    <div class="modal-action">
                                        <form action="{{ route('communities.destroy', $group) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-gradient-error">Ya, Hapus</button>
                                        </form>
                                        <label for="delete-group-{{ $group->id }}" class="btn btn-gradient-neutral">Batal</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full">
                            <div
                                class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-9 4h10m-10 0a2 2 0 110-4h10a2 2 0 110 4m-10 0V20" />
                                </svg>
                                <p class="text-sm text-gray-500">Belum ada komunitas untuk ditampilkan.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="modal-action">
                    <button class="btn btn-gradient-neutral" onclick="groupModal.close()">Tutup</button>
                </div>
            </div>
        </dialog>

    </div>
@endsection
