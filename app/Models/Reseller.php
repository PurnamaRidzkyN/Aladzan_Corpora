<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reseller extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password','phone','pfp_path'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
