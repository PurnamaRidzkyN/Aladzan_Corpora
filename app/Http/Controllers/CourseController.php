<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoGroup;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CourseController extends Controller
{
    public function groupVideoIndex()
    {
        $groups = VideoGroup::all();
        return view('reseller.course.group_video', compact('groups'));
    }
    public function groupVideoStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        $group = VideoGroup::create($request->all());
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

        $group->delete();

        return redirect()->back()->with('success', 'Grup video berhasil dihapus beserta seluruh videonya.');
    }

    public function videoIndex($id)
    {
        $group = VideoGroup::findOrFail($id);
        $videos = Video::where('video_group_id', $id)->get();
        $video_group_id = $id;
        return view('reseller.course.video', compact('group', 'videos', 'video_group_id'));
    }
    public function videoStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'thumbnail' => 'required|file|mimes:jpeg,png,jpg|max:10000',
            'video' => 'required|file|mimes:mp4,mov,avi|max:50000', // sesuaikan dengan kebutuhan
        ]);

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

        Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail_id' => $thumbnail['public_id'],
            'video_id' => $video['public_id'],
            'video_group_id' => $request->video_group_id,
        ]);

        return redirect()->back()->with('success', 'Video berhasil ditambahkan.');
    }
    public function videoUpdate(Request $request, $video_group_id, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg|max:10000',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:50000', // sesuaikan dengan kebutuhan
        ]);
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
        return redirect()->back()->with('success', 'Video berhasil diupdate.');
    }
    public function videoDestroy($id, $video_id)
    {
        $video = Video::where('video_group_id', $id)->where('id', $video_id)->first();
        if ($video) {
            Cloudinary::uploadApi()->destroy($video->video_id, ['resource_type' => 'video']);
            Cloudinary::uploadApi()->destroy($video->thumbnail_id, ['resource_type' => 'image']);
            $video->delete();
        }
        return redirect()->back()->with('success', 'Video berhasil dihapus.');
    }

    public function videoShow($id, $video_id)
    {
        $video = Video::where('video_group_id', $id)->where('id', $video_id)->first();
        return view('reseller.course.video_show', compact('video'));
    }
}
