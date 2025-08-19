<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Community;
use Illuminate\Http\Request;
use App\Helpers\AdminActivityHelper;

class CommunityController extends Controller
{
    public function index()
    {
        $community = Community::latest()->get();
        return view('community.index', compact('community'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'link' => 'required|url',
        ]);

        $community=Community::create($request->all());
        AdminActivityHelper::log('CREATE', 'communities', $community->id, 'Menambahkan grup: ' . $community->name);
        return redirect()->route('communities.index')->with('success', 'Grup berhasil ditambahkan');
    }

    public function destroy(Community $group)
    {
        AdminActivityHelper::log('DELETE', 'communities', $group->id, 'Menghapus grup: ' . $group->name);

        $group->delete();
        return redirect()->route('communities.index')->with('success', 'Grup berhasil dihapus');
    }
}

