<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reseller extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phone', 'pfp_path', 'plan_type'];

    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const PLAN_STANDARD = 0;
    const PLAN_PRO = 1;

    public static $planLabels = [
        self::PLAN_STANDARD => 'Standard',
        self::PLAN_PRO => 'Pro',
    ];

    public function getPlanNameAttribute()
    {
        return self::$planLabels[$this->plan_type] ?? 'Unknown';
    }

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
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
