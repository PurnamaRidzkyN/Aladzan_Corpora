@extends('layouts.dashboard')
@section('title', 'Riwayat Aktivitas Admin')

@php
    $title = 'Riwayat Aktivitas Admin';
    $breadcrumb = [['label' => 'Manajemen Admin'], ['label' => 'Riwayat Aktivitas']];
@endphp

@section('content')
    <section class="w-full lg:px-12 mt-8">
        <div class="card bg-white shadow-md rounded-xl border border-soft">
            <div class="card-body">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-primary">Riwayat Aktivitas</h2>
                </div>

                <!-- Filter -->
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <select name="admin_id" class="select select-bordered w-full">
                        <option value="">Semua Admin</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="action" class="select select-bordered w-full">
                        <option value="">Semua Aksi</option>
                        <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>CREATE</option>
                        <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                        <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                        <option value="LOGIN" {{ request('action') == 'LOGIN' ? 'selected' : '' }}>LOGIN</option>
                        <option value="LOGOUT" {{ request('action') == 'LOGOUT' ? 'selected' : '' }}>LOGOUT</option>
                    </select>

                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="input input-bordered w-full" />
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="input input-bordered w-full" />

                    <!-- Cari Deskripsi -->
                    <input type="text" name="description" value="{{ request('description') }}"
                        placeholder="Cari deskripsi" class="input input-bordered w-full md:col-span-2" />

                    <div class="md:col-span-4 flex gap-2">
                        <button class="btn btn-gradient-primary">Cari</button>
                        <a href="{{ route('admin.activity.index') }}" class="btn btn-gradient-neutral">Reset</a>
                    </div>
                </form>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Admin</th>
                                <th>Aksi</th>
                                <th>Tabel</th>
                                <th>ID Record</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $index => $log)
                                <tr class="hover:bg-accent-light transition">
                                    <td>{{ $logs->firstItem() + $index }}</td>
                                    <td>{{ $log->admin->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-outline badge-primary">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td>{{ $log->table_name }}</td>
                                    <td>{{ $log->record_id }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-gray-500">Belum ada aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{-- Pagination Custom --}}
                    @if ($logs->hasPages())
                        <div class="flex justify-center items-center gap-2 mt-4">

                            {{-- Tombol Prev --}}
                            @if ($logs->onFirstPage())
                                <span class="btn btn-gradient-primary opacity-50 cursor-not-allowed">Prev</span>
                            @else
                                <a href="{{ $logs->previousPageUrl() }}" class="btn btn-gradient-primary">Prev</a>
                            @endif

                            {{-- Nomor Halaman --}}
                            @foreach ($logs->links()->elements[0] ?? [] as $page => $url)
                                @if ($page == $logs->currentPage())
                                    <span class="btn btn-gradient-primary">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-gradient-neutral">{{ $page }}</a>
                                @endif
                            @endforeach

                            {{-- Tombol Next --}}
                            @if ($logs->hasMorePages())
                                <a href="{{ $logs->nextPageUrl() }}" class="btn btn-gradient-primary">Next</a>
                            @else
                                <span class="btn btn-gradient-primary opacity-50 cursor-not-allowed">Next</span>
                            @endif

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
