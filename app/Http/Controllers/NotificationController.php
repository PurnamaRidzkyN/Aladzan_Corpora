<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth('admin')->check() ? auth('admin')->user() : auth('reseller')->user();
        $notifications = $user->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsReadAdmin($id, Request $request)
    {
        $notification = $request->user('admin')->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }
    public function markAsReadReseller($id, Request $request)
    {
        $notification = $request->user('reseller')->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        $user = auth()->guard('admin')->user() ?? auth()->guard('reseller')->user();

        // Tandai semua notifikasi yang belum dibaca
        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
