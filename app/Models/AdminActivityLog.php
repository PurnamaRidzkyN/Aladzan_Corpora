<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
       protected $table = 'admin_activity_logs'; 
    protected $fillable = [
        'admin_id', 'action', 'table_name', 'record_id', 'description'
    ];
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

}
