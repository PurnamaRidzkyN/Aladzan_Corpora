@extends('layouts.dashboard')
@section('title', 'Daftar Reseller')

@section('content')
    <div class="card bg-white shadow-md rounded-xl border border-soft">
        <div class="card-body">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-primary">Daftar Reseller</h2>
            </div>

            <!-- Filter & Search -->
            <form method="GET" action="{{ route('reseller.index') }}" class="form-control w-full mb-4">
                <div class="flex flex-col md:flex-row gap-2">

                    <!-- Search (with mobile button) -->
                    <div class="flex w-full md:w-1/2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..." class="input input-bordered w-full rounded-r-none"
                            onkeydown="if(event.key === 'Enter') this.form.submit()">
                        <!-- Tombol search hanya tampil di mobile -->
                        <button type="submit" class="btn btn-primary md:hidden rounded-l-none">🔍</button>
                    </div>

                    <!-- Filter Member -->
                    <select name="plan_type" class="select select-bordered w-full md:w-1/4" onchange="this.form.submit()">
                        <option value="">Semua Member</option>
                        <option value="1" {{ request('plan_type') == '1' ? 'selected' : '' }}>Pro</option>
                        <option value="0" {{ request('plan_type') == '0' ? 'selected' : '' }}>Standard</option>
                    </select>

                    <!-- Filter Tanggal -->
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="input input-bordered w-full md:w-1/4" onchange="this.form.submit()">
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
                                    @if ($user->plan_type == \App\Models\Reseller::PLAN_PRO)
                                        <span class="badge badge-primary">Pro</span>
                                    @else
                                        <span class="badge badge-neutral">Standard</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <label for="delete-account-{{ $user->id }}"
                                        class="btn btn-error btn-xs text-white">Hapus</label>

                                    <!-- Modal -->
                                    <input type="checkbox" id="delete-account-{{ $user->id }}" class="modal-toggle" />
                                    <div class="modal" role="dialog">
                                        <div class="modal-box">
                                            <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                            <p class="py-4">Yakin ingin menghapus akun
                                                <strong>{{ $user->name }}</strong>?
                                            </p>
                                            <div class="modal-action">
                                                <form action="{{ route('reseller.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-gradient-error">Ya,
                                                        Hapus</button>
                                                </form>
                                                <label for="delete-account-{{ $user->id }}"
                                                    class="btn  btn-gradient-neutral">Batal</label>
                                            </div>
                                        </div>
                                    </div>
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

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </div>
    </div>
@endsection
