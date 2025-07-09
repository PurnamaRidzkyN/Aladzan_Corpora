<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reseller extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'gmail', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
