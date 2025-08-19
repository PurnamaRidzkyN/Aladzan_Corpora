@extends('layouts.dashboard')
@section('title', 'Daftar Reseller')
@php
    $title = 'Daftar Reseller';
    $breadcrumb = [['label' => 'Daftar Reseller']];
@endphp
@section('content')
    <div class="card bg-white shadow-md rounded-xl border border-soft">
        <div class="card-body">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-primary">Daftar Reseller</h2>
            </div>

            <!-- Filter & Search -->
            <form method="GET" action="{{ route('reseller.index') }}" class="w-full mb-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                    <!-- Search -->
                    <div class="md:col-span-2 flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..." class="input input-bordered w-full rounded-r-none"
                            onkeydown="if(event.key === 'Enter') this.form.submit()">
                        <!-- Tombol search mobile -->
                        <button type="submit" class="btn btn-primary md:hidden rounded-l-none"> <i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </div>

                    <!-- Filter Member -->
                    <select name="plan_id" class="select select-bordered w-full" onchange="this.form.submit()">
                        <option value="">Semua Member</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filter Tanggal -->
                    <input type="date" name="date" value="{{ request('date') }}" class="input input-bordered w-full"
                        onchange="this.form.submit()">
                </div>
            </form>


            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table w-full text-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Member</th>
                            <th>Bergabung</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-accent-light transition">
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td class="font-semibold">{{ $user->name }}</td>
                                <td class="text-gray-700">{{ $user->email }}</td>
                                <td>
                                    @if ($user->plan)
                                        @if ($user->plan->name === 'Pro')
                                            <span class="badge badge-primary">Pro</span>
                                        @else
                                            <span class="badge badge-neutral">{{ $user->plan->name }}</span>
                                        @endif
                                    @else
                                        <span class="badge badge-ghost">Belum Ada Plan</span>
                                    @endif

                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    @if ($user->trashed())
                                        {{-- Restore hanya super admin --}}
                                        @if (auth('admin')->user()?->is_super_admin)
                                            <form action="{{ route('reseller.restore', $user->id) }}" method="POST"
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
                        id: {{ $user->id }},
                        name: '{{ $user->name }}',
                        url: '{{ route('reseller.forceDelete', $user->id) }}'
                    })
                ">
                                            Hapus Permanen
                                        </button>
                                    @else
                                        {{-- Tombol hapus (soft delete) --}}
                                        <button class="btn btn-error btn-xs text-white"
                                            @click.prevent="
                    $dispatch('open-delete-modal', {
                        id: {{ $user->id }},
                        name: '{{ $user->name }}',
                        url: '{{ route('reseller.destroy', $user->id) }}'
                    })
                ">
                                            Hapus
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Belum ada reseller yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                            <p class="py-4 text-red-500">Toko <strong x-text="name"></strong> akan dihapus <b>permanen</b>
                                dan
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
            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </div>
    </div>
@endsection
