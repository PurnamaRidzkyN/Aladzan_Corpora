<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'reseller_id', 'product_id', 'quantity'
    ];
}
