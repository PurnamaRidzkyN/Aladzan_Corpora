<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reseller extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    
    protected $fillable = ['name', 'email', 'password', 'phone', 'pfp_path', 'google_id', 'plan_id'];

    protected $hidden = ['password', 'remember_token'];
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
    public function orderSubscriptions()
    {
        return $this->hasMany(OrderSubscription::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function webRating()
    {
        return $this->hasMany(WebRating::class, 'reseller_id', 'reseller_id');
    }
}
