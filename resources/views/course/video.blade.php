@extends('layouts.dashboard')

@section('title', 'Daftar Video')
@php
    $title = $group->name;
    if (auth('reseller')->check()) {
        $breadcrumb = [
            ['label' => 'Group vidio', 'url' => route('reseller.course')],
            ['label' => $group->name],
        ];
    } else {
        $breadcrumb = [
            ['label' => 'Group vidio', 'url' => route('group.course')],
            ['label' => $group->name],
        ];
    }
@endphp


@section('content')
    <div class="container mx-auto p-4 space-y-6" x-data="{
        openModal: false,
        openDeleteModal: false,
        editMode: false,
        form: {
            id: null,
            title: '',
            description: '',
            thumbnail: null,
            video: null,
            previewThumbnail: '',
            previewVideo: ''
        },
        setForm(video) {
            this.form = {
                id: video.id,
                title: video.title,
                description: video.description,
                thumbnail: null,
                video: null,
            };
        }
    }">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Video Pembelajaran</h1>
            @auth('admin')
                <button class="btn btn-primary"
                    @click="openModal = true; editMode = false; form = { id: null, title: '', description: '', thumbnail: null, video: null, previewThumbnail: '', previewVideo: '' }">
                    Tambah Video
                </button>
            @endauth
        </div>

        <!-- Grid Video -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($videos as $video)
                <div class="bg-white border shadow-md rounded-xl overflow-hidden">
                    <img src="{{ cloudinary_url($video->thumbnail_id, 'image', 'w_400,h_250,c_fill') }}"
                        alt="{{ $video->title }}">

                    <div class="p-4 space-y-2">
                        <h2 class="text-lg font-semibold">{{ $video->title }}</h2>
                        <p class="text-sm text-gray-600">{{ $video->description }}</p>

                        <div class="flex justify-between items-center mt-4">
                            @if (auth('admin')->check())
                                <a href="{{ route('group.course.video.show', [$video_group_id, $video->id]) }}"
                                    class="btn btn-gradient-primary">Tonton</a>
                            @elseif (auth('reseller')->check())
                                <a href="{{ route('reseller.course.video.show', [$video_group_id, $video->id]) }}"
                                    class="btn btn-gradient-primary">Tonton</a>
                            @endif

                            @auth('admin')
                                <div class="flex gap-2">
                                    <button class="btn  btn-gradient-warning"
                                        @click="
                                openModal = true;
                                editMode = true;
                                setForm({ 
                                    id: {{ $video->id }},
                                    title: '{{ $video->title }}',
                                    description: `{{ $video->description }}`,
                                    thumbnail_id: '{{ $video->thumbnail_id }}'
                                })">Edit
                                        <!-- Tombol Buka Modal -->
                                        <button @click="openDeleteModal = true" class="btn btn-gradient-error">
                                            Hapus
                                        </button>
                                        <!-- Modal Custom -->
                                        <div x-show="openDeleteModal"
                                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                            x-cloak>
                                            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                                                <h2 class="text-xl font-semibold mb-4">Konfirmasi Hapus</h2>
                                                <p class="mb-6">Apakah kamu yakin ingin menghapus video
                                                    <strong>{{ $video->title }}</strong>?
                                                </p>
                                                <div class="flex justify-end gap-2">
                                                    <form method="POST"
                                                        action="{{ route('group.course.video.destroy', [$video_group_id, $video->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-gradient-error">
                                                            Ya, Hapus
                                                        </button>
                                                    </form>
                                                    <button @click="openDeleteModal = false" class="btn btn-gradient-neutral">
                                                        Batal
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($videos->isEmpty())
            <div class="w-full">
                <div
                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-9 4h10m-10 0a2 2 0 110-4h10a2 2 0 110 4m-10 0V20" />
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada video untuk ditampilkan.</p>
                </div>
            </div>
        @endif


        <!-- Modal -->
        <div class="modal" :class="{ 'modal-open': openModal }">
            <div class="modal-box max-w-2xl">
                <h3 class="font-bold text-lg mb-4" x-text="editMode ? 'Edit Video' : 'Tambah Video'"></h3>

                <form
                    :action="editMode
                        ?
                        '/staff-only/course/{{ $video_group_id }}/video/' + form.id :
                        '{{ route('group.course.video.store', $video_group_id) }}'"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <template x-if="editMode">
                        @method('PUT')
                    </template>

                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Judul -->
                    <div class="form-control mb-5">
                        <label class="label font-semibold text-base">Judul Video</label>
                        <input name="title" type="text" class="input input-bordered w-full" x-model="form.title"
                            required>
                    </div>
                    <input type="hidden" name="video_group_id" value="{{ $video_group_id }}">

                    <!-- Deskripsi -->
                    <div class="form-control mb-8">
                        <label class="label font-semibold text-base">Deskripsi</label>
                        <textarea name="description" class="textarea textarea-bordered w-full min-h-[100px]" x-model="form.description"
                            required></textarea>
                    </div>

                    <!-- Wrapper Thumbnail + Video -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Thumbnail -->
                        <div class="form-control">
                            <label class="label font-semibold text-base">Thumbnail</label>
                            <input name="thumbnail" type="file" class="file-input file-input-bordered w-full"
                                accept="image/*"
                                @change="
                                form.thumbnail = $event.target.files[0];
                                form.previewThumbnail = URL.createObjectURL(form.thumbnail);
                            ">
                            <template x-if="form.previewThumbnail">
                                <img :src="form.previewThumbnail"
                                    class="mt-2 w-full h-40 object-cover rounded-lg border shadow-sm" />
                            </template>
                        </div>

                        <!-- Video -->
                        <div class="form-control">
                            <label class="label font-semibold text-base">Video</label>
                            <input name="video" type="file" class="file-input file-input-bordered w-full"
                                accept="video/*"
                                @change="
                                form.video = $event.target.files[0];
                                form.previewVideo = URL.createObjectURL(form.video);
                            ">
                            <template x-if="form.previewVideo">
                                <video :src="form.previewVideo" controls
                                    class="mt-2 w-full h-40 object-cover rounded-lg border shadow-sm"></video>
                            </template>
                        </div>
                    </div>
                    <template x-if="editMode">
                        <p class="text-sm text-gray-500 mt-1">
                            Jika ingin mengganti thumbnail atau video, silakan pilih file baru. Jika tidak, biarkan kosong.
                        </p>
                    </template>
                    <!-- Actions -->
                    <div class="modal-action">
                        <button type="button" class="btn btn-gradient-neutral" @click="openModal = false">Batal</button>
                        <button type="submit" class="btn btn-gradient-primary"
                            x-text="editMode ? 'Simpan Perubahan' : 'Tambah Video'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
