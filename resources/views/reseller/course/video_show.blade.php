@extends('layouts.dashboard')

@section('title', $video->title)
@php
    $title = $video->title;
    if (auth('reseller')->check()) {
        $breadcrumb = [
            ['label' => 'Group vidio', 'url' => route('reseller.course')],
            ['label' => $video->group->name, 'url' => route('reseller.course.video', $video->group->id)],
            ['label' => $video->title],
        ];
    } else {
        $breadcrumb = [
            ['label' => 'Group vidio', 'url' => route('group.course')],
            ['label' => $video->group->name, 'url' => route('group.course.video', $video->group->id)],
            ['label' => $video->title],
        ];
    }
@endphp
@section('content')
    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">

        <!-- Cloudinary Video Player -->
        <div class="relative w-full aspect-video overflow-hidden rounded-xl shadow" oncontextmenu="return false;">
            <video src="{{ cloudinary_url($video->video_id, 'video') }}" controls
                class="w-full aspect-video rounded-xl shadow-lg border border-gray-200"
                poster="{{ cloudinary_url($video->thumbnail_id, 'image') }}" oncontextmenu="return false;">
                Your browser does not support the video tag.
            </video>

        </div>

        <!-- Judul dan Deskripsi -->
        <div class="mt-6">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight leading-tight">
                {{ $video->title }}
            </h1>
            <p class="text-base text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                {{ $video->description }}
            </p>
        </div>

    </div>
@endsection
