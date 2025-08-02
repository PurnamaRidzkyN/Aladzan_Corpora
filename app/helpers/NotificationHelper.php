<?php
namespace App\Helpers;

use App\Models\Admin;
use App\Models\Reseller;
use App\Notifications\GeneralNotification;

class NotificationHelper
{
    // Kirim notifikasi ke semua admin
    public static function notifyAdmins($title, $message, $link = null)
    {
        $admins = Admin::all();
        foreach ($admins as $admin) {
            $admin->notify(new GeneralNotification($title, $message, $link));
        }
    }

    // Kirim notifikasi ke reseller tertentu
    public static function notifyReseller(Reseller $reseller, $title, $message, $link = null)
    {
        $reseller->notify(new GeneralNotification($title, $message, $link));
    }
}

