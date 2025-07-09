<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{


    protected $fillable = [
        'reseller_id', 'total_amount', 'status', 'paid_at', 'address_id'
    ];
}
