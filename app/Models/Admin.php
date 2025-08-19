<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name','email', 'password', 'is_super_admin',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
    public function activityLogs()
    {
        return $this->hasMany(AdminActivityLog::class, 'admin_id');
    }
}
