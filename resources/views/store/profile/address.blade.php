@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{ openChoose: false, openDelete: false, actionChoose: '', actionDelete: '' }">
        <!-- Judul utama -->
        <h1 class="text-2xl font-bold text-primary mb-4">Daftar Alamat</h1>

        <!-- Baris tombol aksi -->
        <div class="flex justify-between items-center mb-6">
            <!-- Tombol kembali -->
            @if (!$chooseeAddress)
                <a href="{{ route('profile') }}" class="btn btn-gradient-neutral">
                    ← Kembali
                </a>
            @endif

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
                            @if (!empty($chooseeAddress))
                                {
                                <button type="button" class="btn btn-sm btn-gradient-primary"
                                    @click="
                                    actionChoose='{{ route('checkout') }}';
                                    openChoose=true;
                                ">
                                    Pilih
                                </button>

                                }
                            @endif

                            <!-- Tombol EDIT -->
                            <button class="btn btn-sm btn-gradient-warning" onclick="editAddress({{ $address }})">
                                ✏️ Edit
                            </button>

                            <!-- Tombol HAPUS -->
                            <button type="button" class="btn btn-sm btn-gradient-error"
                                @click="
                                    actionDelete='{{ route('address.destroy', $address) }}';
                                    openDelete=true;
                                ">
                                🗑️ Hapus
                            </button>


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
                <div class=" col-span-full text-center py-10 w-full">
                    <div
                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-2.293 2.293a1 1 0 01-.707.293H6a1 1 0 01-.707-.293L3 13m17 0V17a2 2 0 01-2 2H6a2 2 0 01-2-2v-4" />
                        </svg>

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-9 4h10m-10 0a2 2 0 110-4h10a2 2 0 110 4m-10 0V20" />
                        </svg>
                        <p class="text-sm text-gray-500">Belum ada alamat yang ditambahkan.</p>
                    </div>
                </div>
            @endforelse
        </div>
        @if (!empty($chooseeAddress))
            {
            <!-- MODAL PILIH -->
            <div x-show="openChoose" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                x-transition>
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <h3 class="font-bold text-lg">Konfirmasi</h3>
                    <p class="py-4">Pilih alamat ini?</p>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="btn btn-gradient-neutral" @click="openChoose=false">Batal</button>
                        <form method="POST" :action="actionChoose">
                            @csrf
                            <input type="hidden" name="items_json" value='{{ json_encode($cartItemIds) }}'>
                            <input type="hidden" name="address_id" value="{{ $address->id }}">
                            <button type="submit" class="btn btn-gradient-success">Ya</button>
                        </form>
                    </div>
                </div>
            </div>
            }
        @endif
        <!-- MODAL HAPUS -->
        <div x-show="openDelete" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            x-transition>
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                <h3 class="font-bold text-lg">Konfirmasi</h3>
                <p class="py-4">Hapus alamat ini?</p>
                <div class="flex justify-end gap-2">
                    <button type="button" class="btn btn-gradient-neutral" @click="openDelete=false">Batal</button>
                    <form method="POST" :action="actionDelete">
                        @csrf
                        @method('DELETE')
                        @if ($chooseeAddress)
                            <input type="hidden" name="items_json" value='@json($items)'>
                        @endif
                        <button type="submit" class="btn btn-gradient-error">Hapus</button>
                    </form>
                </div>
            </div>
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
                @if ($chooseeAddress)
                    <input type="hidden" name="items_json" value='@json($items)'>
                @endif
                <div class="modal-action flex justify-end gap-2">
                    <button type="submit" class="btn btn-gradient-primary">Simpan</button>
                    <button type="button" class="btn btn-gradient-neutral" onclick="resetModal()">Batal</button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- JavaScript --}}
    <script>
        const modal = document.getElementById('addModal');
        const form = document.getElementById('addressForm');
        const modalTitle = document.getElementById('modalTitle');

        function editAddress(address) {
            modalTitle.innerText = 'Edit Alamat';
            form.action = `/address/${address.id}`;
            form.querySelector('input[name="_method"]')?.remove();
            form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');

            // Isi form dengan data address
            for (const key in address) {
                const input = document.getElementById('form_' + key);
                if (input) input.value = address[key] ?? '';
            }

            modal.showModal();
        }

        function resetModal() {
            // Reset title & form action
            modalTitle.innerText = 'Tambah Alamat';
            form.action = `{{ route('address.store') }}`;
            form.querySelector('input[name="_method"]')?.remove();

            // Kosongkan semua input
            form.reset();

            // Tutup modal
            modal.close();
        }
    </script>
@endsection
