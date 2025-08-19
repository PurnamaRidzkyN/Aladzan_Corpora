@extends('layouts.dashboard')
@section('title', 'Grup Video')


@php
    $title = 'Pembelajaran';
    $breadcrumb = [['label' => 'Grup Video']];
@endphp

@section('content')
    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-6 overflow-x-hidden">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Grup Video Pembelajaran</h1>

            @if (auth('admin')->check())
                <button onclick="openModal('add')" class="btn btn-primary">+ Tambah Grup</button>
            @endif
        </div>

        @if ($groups->isEmpty())
             <div class="w-full">
                <div
                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-9 4h10m-10 0a2 2 0 110-4h10a2 2 0 110 4m-10 0V20" />
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada group video untuk ditampilkan.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($groups as $group)
                    <div class="card w-full bg-white shadow-md rounded-xl">
                        <div class="card-body">
                            <h2 class="font-semibold text-lg">{{ $group->name }}</h2>
                            <p class="text-gray-500 text-sm mb-4">{{ $group->description }}</p>

                            <div class="flex justify-between items-center mt-4">
                                @if (auth('admin')->check())
                                    <a href="{{ route('group.course.video', $group->id) }}"
                                        class="btn-gradient-primary">Lihat Video</a>
                                @elseif (auth('reseller')->check())
                                    <a href="{{ route('reseller.course.video', $group->id) }}"
                                        class="btn-gradient-primary">Lihat Video</a>
                                @endif

                                @if (auth('admin')->check())
                                    <div class="flex space-x-2">
                                        <button
                                            onclick="openModal('edit', {{ $group->id }}, `{{ $group->name }}`, `{{ $group->description }}`)"
                                            class="btn btn-gradient-warning">
                                            Edit
                                        </button>

                                        <form action="{{ route('group.course.destroy', $group->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-gradient-error">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Modal Tunggal -->
        <input type="checkbox" id="group-modal" class="modal-toggle" />
        <div class="modal" role="dialog">
            <div class="modal-box">
                <h3 class="font-bold text-lg" id="modal-title">Tambah Grup</h3>

                <form id="group-form" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Grup</label>
                        <input type="text" name="name" id="form-name" class="input input-bordered w-full" required>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="form-description" class="textarea textarea-bordered w-full" required></textarea>
                    </div>

                    <div class="modal-action">
                        <label for="group-modal" class="btn btn-gradient-neutral">Batal</label>
                        <button type="submit" class="btn btn-gradient-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(mode, id = null, name = '', description = '') {
            const modalCheckbox = document.getElementById('group-modal');
            const form = document.getElementById('group-form');
            const methodInput = document.getElementById('form-method');
            const nameInput = document.getElementById('form-name');
            const descInput = document.getElementById('form-description');
            const title = document.getElementById('modal-title');

            nameInput.value = name;
            descInput.value = description;

            if (mode === 'add') {
                title.textContent = 'Tambah Grup Video';
                methodInput.value = 'POST';
                form.action = "{{ route('group.course.store') }}";
            } else if (mode === 'edit') {
                title.textContent = 'Edit Grup Video';
                methodInput.value = 'PUT';
                form.action = `/staff-only/course/${id}`;
            }

            modalCheckbox.checked = true;
        }
    </script>
@endsection
