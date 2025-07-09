@extends('layouts.dashboard')
@section('title', 'Daftar Reseller')

@section('content')
@php
    $users = [
        [
            'id' => 1,
            'name' => 'Ayu Kusuma',
            'email' => 'ayu.kusuma@example.com',
            'membership' => 'pro',
            'created_at' => now()->subDays(3),
        ],
        [
            'id' => 2,
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'membership' => 'reguler',
            'created_at' => now()->subDays(10),
        ],
        [
            'id' => 3,
            'name' => 'Citra Lestari',
            'email' => 'citra.lestari@example.com',
            'membership' => 'pro',
            'created_at' => now()->subMonths(1),
        ],
        [
            'id' => 4,
            'name' => 'Dimas Saputra',
            'email' => 'dimas.saputra@example.com',
            'membership' => 'reguler',
            'created_at' => now()->subWeeks(2),
        ],
        [
            'id' => 5,
            'name' => 'Eka Putri',
            'email' => 'eka.putri@example.com',
            'membership' => 'pro',
            'created_at' => now()->subDays(7),
        ],
    ];
@endphp

<div class="card bg-white shadow-md rounded-xl border border-soft">
    <div class="card-body">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-primary">Daftar Reseller</h2>
            {{-- Bisa tambah tombol jika perlu --}}
        </div>

        <!-- Filter & Search -->
        <div class="form-control w-full mb-4">
            <div class="flex flex-col md:flex-row gap-2">
                <input type="text" placeholder="Cari nama atau email..." class="input input-bordered w-full md:w-1/2">
                <select class="select select-bordered w-full md:w-1/4">
                    <option value="">Semua Member</option>
                    <option value="pro">Pro</option>
                    <option value="reguler">Reguler</option>
                </select>
                <input type="date" class="input input-bordered w-full md:w-1/4">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table w-full text-sm">
                <thead class="theadisplay">
                    <tr>
                        <th class="py-3">#</th>
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
                        <td class="py-2">{{ $index + 1 }}</td>
                        <td class="font-semibold">{{ $user['name'] }}</td>
                        <td class="text-gray-700">{{ $user['email'] }}</td>
                        <td>
                            @if($user['membership'] === 'pro')
                                <span class="badge badge-primary">Pro</span>
                            @else
                                <span class="badge badge-neutral">Reguler</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($user['created_at'])->format('d M Y') }}</td>
                        <td class="text-center space-x-2">
                            <a href="" class="btn btn-success btn-xs text-white">Lihat</a>
                            <label for="delete-user-{{ $user['id'] }}" class="btn btn-error btn-xs text-white">Hapus</label>

                            <!-- Modal konfirmasi -->
                            <input type="checkbox" id="delete-user-{{ $user['id'] }}" class="modal-toggle" />
                            <div class="modal" role="dialog">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                    <p class="py-4">Yakin ingin menghapus akun <strong>{{ $user['name'] }}</strong>?</p>
                                    <div class="modal-action">
                                        <form action="" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-gradient-error">Ya, Hapus</button>
                                        </form>
                                        <label for="delete-user-{{ $user['id'] }}" class="btn">Batal</label>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Belum ada reseller yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
