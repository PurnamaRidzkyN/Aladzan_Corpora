@extends('layouts.dashboard')
@section('title', 'Manajemen Diskon')
@php
    $title = 'Manajemen Diskon';
    $breadcrumb = [['label' => 'Manajemen Diskon']];
@endphp
@section('content')
    <section class="w-full lg:px-12 mt-8">
        <div class="card bg-white shadow-md rounded-xl border border-soft">
            <div class="card-body">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-primary">Diskon</h2>
                    <label for="modal_kategori" class="btn btn-sm btn-gradient-primary border-none">
                        + Tambah Diskon
                    </label>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead class="theadisplay">
                            <tr>
                                <th class="py-3">#</th>
                                <th>Kode Diskon</th>
                                <th>Jumlah Diskon</th>
                                <th>Tipe Diskon</th>
                                <th>Berakhir Diskon</th>
                                <th>Tanggal Diskon</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($discount as $index => $discount)
                                <tr class="hover:bg-accent-light transition">
                                    <td class="py-2">{{ $index + 1 }}</td>
                                    <td>{{ $discount->code }}</td>
                                    <td>{{ $discount->amount }}</td>
                                    <td>{{ $discount->is_percent ? 'Persentase' : 'Potongan Langsung' }}</td>
                                    <td>{{ $discount->valid_until }}</td>
                                    <td>{{ $discount->created_at }}</td>
                                    <td class="text-center space-x-2">

                                        <!-- Tombol Hapus -->
                                        <label for="delete-modal-{{ $discount->id }}"
                                            class="btn btn-error btn-xs text-white">Hapus</label>

                                        <!-- Modal Konfirmasi -->
                                        <input type="checkbox" id="delete-modal-{{ $discount->id }}" class="modal-toggle" />
                                        <div class="modal" role="dialog">
                                            <div class="modal-box">
                                                <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                                <p class="py-4">Apakah kamu yakin ingin menghapus discont
                                                    <strong>{{ $discount->code }}</strong>?
                                                </p>
                                                <div class="modal-action">
                                                    <form method="POST"
                                                        action="{{ route('discount.destroy', $discount->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-gradient-error">Ya, Hapus</button>
                                                    </form>
                                                    <label for="delete-modal-{{ $discount->id }}"
                                                        class="btn  btn-gradient-neutral">Batal</label>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500 py-6">
                                        Belum ada diskon.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </section>


    <!-- Modal Tambah Diskon -->
    <input type="checkbox" id="modal_kategori" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box w-full max-w-md">
            <h3 class="font-bold text-lg mb-2 text-primary">Tambah Diskon</h3>
            <form action="{{ route('discount.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="code" placeholder="Kode Diskon" class="input input-bordered w-full" required>
                <input type="number" name="amount" placeholder="Jumlah Diskon" class="input input-bordered w-full"
                    required>
                <select name="type" class="select select-bordered w-full">
                    <option value="" disabled selected>Pilih Tipe Diskon</option>
                    <option value="0">Potongan Langsung (Rp)</option>
                    <option value="1">Persentase (%)</option>
                </select>
                <input type="date" name="valid_until" placeholder="Berakhir Diskon" class="input input-bordered w-full"
                    required>

                <div class="modal-action">
                    <label for="modal_kategori" class="btn  btn-gradient-neutral">Batal</label>
                    <button type="submit" class="btn btn-gradient-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


@endsection
