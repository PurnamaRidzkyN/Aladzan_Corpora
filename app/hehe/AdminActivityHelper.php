<?php
namespace App\helpers;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminActivityHelper
{
    /**
     * Catat aktivitas admin
     *
     * @param string $action       CREATE, UPDATE, DELETE, LOGIN, LOGOUT, dll.
     * @param string $tableName    Nama tabel yang diubah
     * @param int|null $recordId   ID record yang diubah
     * @param string|null $description Deskripsi tambahan
     */
    public static function log($action, $tableName, $recordId = null, $description = null)
    {
        AdminActivityLog::create([
            'admin_id'    => Auth::guard('admin')->id(),
            'action'      => $action,
            'table_name'  => $tableName,
            'record_id'   => $recordId,
            'description' => $description,
        ]);
    }
}
