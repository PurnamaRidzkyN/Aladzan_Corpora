<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoGroup;
use Illuminate\Http\Request;
use App\Helpers\AdminActivityHelper;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CourseController extends Controller
{
    public function groupVideoIndex()
    {
        $groups = VideoGroup::all();
        return view('course.group_video', compact('groups'));
    }
    public function groupVideoStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        $group = VideoGroup::create($request->all());
        // Log aktivitas admin
        AdminActivityHelper::log('CREATE', 'video_groups', $group->id, 'Menambahkan grup video: ' . $request->name);
        return redirect()->back()->with('success', 'Grup video berhasil ditambahkan.');
    }
    public function groupVideoUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        $group = VideoGroup::findOrFail($id);
        $group->update($request->all());
        // Log aktivitas admin
        AdminActivityHelper::log('UPDATE', 'video_groups', $group->id, 'Mengupdate grup video: ' . $request->name);
        return redirect()->back()->with('success', 'Grup video berhasil diupdate.');
    }
    public function groupVideoDestroy($id)
    {
        $group = VideoGroup::findOrFail($id);

        $videos = Video::where('video_group_id', $id)->get();

        foreach ($videos as $video) {
            if ($video->video_id) {
                Cloudinary::uploadApi()->destroy($video->video_id, ['resource_type' => 'video']);
            }

            if ($video->thumbnail_id) {
                Cloudinary::uploadApi()->destroy($video->thumbnail_id, ['resource_type' => 'image']);
            }

            $video->delete();
        }
        // Log aktivitas admin
        AdminActivityHelper::log('DELETE', 'video_groups', $group->id, 'Menghapus grup video: ' . $group->name);
        $group->delete();

        return redirect()->back()->with('success', 'Grup video berhasil dihapus beserta seluruh videonya.');
    }

    public function videoIndex($id)
    {
        $group = VideoGroup::findOrFail($id);
        $videos = Video::where('video_group_id', $id)->get();
        $video_group_id = $id;
        return view('course.video', compact('group', 'videos', 'video_group_id'));
    }
    public function videoStore(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg|max:10000',
                'video' => 'required|file|mimes:mp4,mov,avi|max:100000',
            ],
            [
                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.max' => 'Judul maksimal 255 karakter.',

                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',
                'description.max' => 'Deskripsi maksimal 255 karakter.',

                'thumbnail.required' => 'Thumbnail wajib diunggah.',
                'thumbnail.file' => 'Thumbnail harus berupa file.',
                'thumbnail.mimes' => 'Thumbnail harus berformat jpeg, png, atau jpg.',
                'thumbnail.max' => 'Thumbnail maksimal 10 MB.',

                'video.required' => 'Video wajib diunggah.',
                'video.file' => 'Video harus berupa file.',
                'video.mimes' => 'Video harus berformat mp4, mov, atau avi.',
                'video.max' => 'Video maksimal 100 MB.',
            ],
        );

        $folderName = 'C' . '/G' . $request->video_group_id;

        // Upload thumbnail
        $thumbnail = Cloudinary::uploadApi()->upload($request->file('thumbnail')->getRealPath(), [
            'folder' => $folderName,
            'resource_type' => 'image',
        ]);

        // Upload video
        $video = Cloudinary::uploadApi()->upload($request->file('video')->getRealPath(), [
            'folder' => $folderName,
            'resource_type' => 'video',
        ]);

        $v = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail_id' => $thumbnail['public_id'],
            'video_id' => $video['public_id'],
            'video_group_id' => $request->video_group_id,
        ]);
        // Log aktivitas admin
        AdminActivityHelper::log('CREATE', 'videos', $v->id, 'Menambahkan video: ' . $request->title);
        return redirect()->back()->with('success', 'Video berhasil ditambahkan.');
    }
    public function videoUpdate(Request $request, $video_group_id, $id)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg|max:10000',
                'video' => 'required|file|mimes:mp4,mov,avi|max:100000',
            ],
            [
                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.max' => 'Judul maksimal 255 karakter.',

                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',
                'description.max' => 'Deskripsi maksimal 255 karakter.',

                'thumbnail.required' => 'Thumbnail wajib diunggah.',
                'thumbnail.file' => 'Thumbnail harus berupa file.',
                'thumbnail.mimes' => 'Thumbnail harus berformat jpeg, png, atau jpg.',
                'thumbnail.max' => 'Thumbnail maksimal 10 MB.',

                'video.required' => 'Video wajib diunggah.',
                'video.file' => 'Video harus berupa file.',
                'video.mimes' => 'Video harus berformat mp4, mov, atau avi.',
                'video.max' => 'Video maksimal 100 MB.',
            ],
        );

        $video = Video::where('video_group_id', $video_group_id)->where('id', $id)->first();
        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail_id) {
                Cloudinary::uploadApi()->destroy($video->thumbnail_id, ['resource_type' => 'image']);
            }

            $thumbnail = Cloudinary::uploadApi()->upload($request->file('thumbnail')->getRealPath(), [
                'folder' => 'C' . '/G' . $video_group_id,
                'resource_type' => 'image',
            ]);
            $video->update([
                'thumbnail_id' => $thumbnail['public_id'],
            ]);
        }
        if ($request->hasFile('video')) {
            if ($video->video_id) {
                Cloudinary::uploadApi()->destroy($video->video_id, ['resource_type' => 'video']);
            }
            $video = Cloudinary::uploadApi()->upload($request->file('video')->getRealPath(), [
                'folder' => 'C' . '/G' . $video_group_id,
                'resource_type' => 'video',
            ]);
            $video->update([
                'video_id' => $video['public_id'],
            ]);
        }
        $video->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        // Log aktivitas admin
        AdminActivityHelper::log('UPDATE', 'videos', $video->id, 'Mengupdate video: ' . $request->title);
        return redirect()->back()->with('success', 'Video berhasil diupdate.');
    }
    public function videoDestroy($id, $video_id)
    {
        $video = Video::where('video_group_id', $id)->where('id', $video_id)->first();
        if ($video) {
            Cloudinary::uploadApi()->destroy($video->video_id, ['resource_type' => 'video']);
            Cloudinary::uploadApi()->destroy($video->thumbnail_id, ['resource_type' => 'image']);
            // Log aktivitas admin
            AdminActivityHelper::log('DELETE', 'videos', $video->id, 'Menghapus video: ' . $video->title);
            $video->delete();
        }
        return redirect()->back()->with('success', 'Video berhasil dihapus.');
    }

    public function videoShow($id, $video_id)
    {
        $video = Video::where('video_group_id', $id)->where('id', $video_id)->first();
        return view('course.video_show', compact('video'));
    }
}
