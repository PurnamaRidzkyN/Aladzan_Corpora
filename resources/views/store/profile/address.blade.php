@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Judul utama -->
        <h1 class="text-2xl font-bold text-primary mb-4">Daftar Alamat</h1>

        <!-- Baris tombol aksi -->
        <div class="flex justify-between items-center mb-6">
            <!-- Tombol kembali -->
            <a href="{{ route('profile') }}" class="btn btn-gradient-neutral">
                ← Kembali
            </a>

            <!-- Tombol tambah alamat -->
            <button class="btn-gradient-primary" onclick="addModal.showModal()">
                + Tambah Alamat
            </button>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($addresses as $address)
                <div class="bg-white shadow-lg rounded-2xl border border-blue-100 p-4 sm:p-6 space-y-4">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-blue-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C8.13401 2 5 5.13401 5 9c0 4.243 7 13 7 13s7-8.757 7-13c0-3.866-3.134-7-7-7z">
                                    </path>
                                    <circle cx="12" cy="9" r="2.5"></circle>
                                </svg>
                                {{ $address->recipient_name }}
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                📞 <span class="font-medium">{{ $address->phone_number }}</span>
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('checkout') }}">
                                @csrf
                                @method('post')
                                @if ($chooseeAddress)
                                    <input type="hidden" name="items_json" id="itemsJson"
                                        value='{{ json_encode($cartItemIds) }}'>
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                    <button class="btn btn-sm  btn-gradient-error"
                                        onclick="return confirm('Pilih alamat ini?')">
                                        Pilih
                                    </button>
                                @endif
                            </form>
                            <button class="btn btn-sm  btn-gradient-warning" onclick="editAddress({{ $address }})">
                                ✏️ Edit
                            </button>
                            <form method="POST" action="{{ route('address.destroy', $address) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm  btn-gradient-error"
                                    onclick="return confirm('Hapus alamat ini?')">
                                    🗑️ Hapus
                                </button>
                            </form>

                        </div>
                    </div>

                    <!-- Address Detail -->
                    <div class="text-sm text-gray-700 leading-relaxed space-y-1 border-t pt-4">
                        <p><strong>Alamat:</strong> {{ $address->address_detail }}</p>
                        @if ($address->kampung)
                            <p><strong>Kampung:</strong> {{ $address->kampung }}</p>
                        @endif
                        <p><strong>RT/RW:</strong> {{ $address->neighborhood ?? '-' }}/{{ $address->hamlet ?? '-' }}</p>
                        <p><strong>Kelurahan/Desa:</strong> {{ $address->village }}</p>
                        <p><strong>Kecamatan:</strong> {{ $address->district }}</p>
                        <p><strong>Kota/Kabupaten:</strong> {{ $address->city }}</p>
                        <p><strong>Provinsi:</strong> {{ $address->province }}</p>
                        <p><strong>Kode Pos:</strong> {{ $address->zipcode }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">Belum ada alamat.</div>
            @endforelse
        </div>


    </div>

    {{-- Modal Tambah/Edit --}}
    <dialog id="addModal" class="modal">
        <div class="modal-box max-w-xl rounded-2xl shadow-lg bg-white">
            <form method="POST" action="{{ route('address.store') }}" id="addressForm" class="space-y-4">
                @csrf

                <h3 id="modalTitle" class="text-xl font-semibold text-blue-700">Tambah Alamat</h3>

                <input type="hidden" name="id" id="form_id">
                <input type="hidden" name="reseller_id" id="form_reseller_id" value="{{ auth()->user()->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">Nama Penerima</label>
                        <input type="text" name="recipient_name" id="form_recipient_name"
                            class="input input-bordered w-full" required />
                    </div>

                    <div class="form-control">
                        <label class="label">Nomor HP</label>
                        <input type="text" name="phone_number" id="form_phone_number" class="input input-bordered w-full"
                            required />
                    </div>

                    <div class="form-control">
                        <label class="label">RT</label>
                        <input type="text" name="neighborhood" id="form_neighborhood" class="input input-bordered w-full"
                            required />
                    </div>

                    <div class="form-control">
                        <label class="label">RW</label>
                        <input type="text" name="hamlet" id="form_hamlet" class="input input-bordered w-full"
                            required />
                    </div>

                    <div class="form-control">
                        <label class="label">Kampung</label>
                        <input type="text" name="village" id="form_village" class="input input-bordered w-full"
                            required />
                    </div>

                    <div class="form-control">
                        <label class="label">Kode Pos</label>
                        <input type="text" name="zipcode" id="form_zipcode" class="input input-bordered w-full"
                            required />
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label">Alamat Lengkap (Nama Jalan/Nomor)</label>
                        <textarea name="address_detail" id="form_address_detail" class="textarea textarea-bordered w-full resize-none"
                            rows="2" required></textarea>
                    </div>
                </div>

                <div class="modal-action flex justify-end gap-2">
                    <button type="submit" class="btn btn-gradient-primary">Simpan</button>
                    <button type="button" class="btn btn-gradient-neutral" onclick="addModal.close()">Batal</button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- JavaScript --}}
    <script>
        function editAddress(address) {
            const modal = document.getElementById('addModal');
            document.getElementById('modalTitle').innerText = 'Edit Alamat';

            const form = document.getElementById('addressForm');
            form.action = `/profil/address/${address.id}`;
            // Hapus _method jika sudah ada agar tidak dobel
            form.querySelector('input[name="_method"]')?.remove();
            // Tambahkan PUT untuk edit
            form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');

            // Set value
            for (const key in address) {
                const input = document.getElementById('form_' + key);
                if (input) input.value = address[key] ?? '';
            }

            modal.showModal();
        }
    </script>
@endsection
