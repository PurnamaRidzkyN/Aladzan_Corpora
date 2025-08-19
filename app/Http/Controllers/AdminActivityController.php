<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\AdminActivityLog;

class AdminActivityController extends Controller
{

    public function index(Request $request)
{
    $admins = Admin::all();

    $logs = AdminActivityLog::with('admin')
        ->when($request->admin_id, fn($q) => $q->where('admin_id', $request->admin_id))
        ->when($request->action, fn($q) => $q->where('action', $request->action))
        ->when($request->start_date && $request->end_date, function($q) use ($request) {
            $q->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        })
        ->when($request->description, fn($q) => $q->where('description', 'like', '%' . $request->description . '%'))
        ->latest()
        ->paginate(50)
        ->withQueryString();

    return view('admin.activity.index', compact('logs', 'admins'));
}

}