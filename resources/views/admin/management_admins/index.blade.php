@extends('layouts.dashboard')
@section('title', 'Kategori Produk')

@section('content')
    <section class="w-full lg:px-12 mt-8">
        <div class="card bg-white shadow-md rounded-xl border border-soft">
            <div class="card-body">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-primary">Manajemen Admin</h2>
                    <label for="modal_admin" class="btn btn-sm text-white btn-gradient-primary border-none">
                        + Tambah Admin
                    </label>
                </div>

                <!-- Search -->
                <div class="form-control w-full mb-4">
                    <input type="text" id="adminSearch" placeholder="Cari admin..." class="input input-bordered w-full" />
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead>
                            <tr>
                                <th class="py-3">#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Super Admin</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="adminTable">
                            @forelse ($admins as $index => $admin)
                                <tr class="hover:bg-accent-light transition">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="admin-name">{{ $admin->name }}</td>
                                    <td class="admin-email">{{ $admin->email }}</td>
                                    <td>
                                        @if ($admin->is_super_admin)
                                            <span class="badge badge-success">Ya</span>
                                        @else
                                            <span class="badge badge-ghost">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="text-center space-x-2">
                                        <label for="delete-admin-{{ $admin->id }}"
                                            class="btn btn-error btn-xs text-white">Hapus</label>

                                        <!-- Modal Konfirmasi Hapus -->
                                        <input type="checkbox" id="delete-admin-{{ $admin->id }}" class="modal-toggle" />
                                        <div class="modal" role="dialog">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                                <p class="py-4">Yakin ingin menghapus admin
                                                    <strong>{{ $admin->name }}</strong>?</p>
                                                <div class="modal-action">
                                                    <form action="{{ route('admins.destroy', $admin->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-gradient-error">Ya,
                                                            Hapus</button>
                                                    </form>
                                                    <label for="delete-admin-{{ $admin->id }}"
                                                        class="btn">Batal</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-6">
                                        Belum ada admin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Modal Tambah Admin -->
        <input type="checkbox" id="modal_admin" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box w-full max-w-md">
                <h3 class="font-bold text-lg mb-2 text-primary">Tambah Admin</h3>
                <form action="{{ route('admins.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nama" class="input input-bordered w-full" required>
                    <input type="email" name="email" placeholder="Email" class="input input-bordered w-full" required>
                    <div class="modal-action">
                        <button class="btn btn-gradient-primary text-white">Simpan</button>
                        <label for="modal_admin" class="btn">Batal</label>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('adminSearch');
            const rows = document.querySelectorAll('#adminTable tr');

            input.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();

                rows.forEach(row => {
                    const name = row.querySelector('.admin-name')?.textContent.toLowerCase() || '';
                    const email = row.querySelector('.admin-email')?.textContent.toLowerCase() ||
                    '';

                    if (name.includes(keyword) || email.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
