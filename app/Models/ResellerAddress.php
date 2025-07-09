<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerAddress extends Model
{
    protected $fillable = [
        'reseller_id', 'label', 'address', 'city', 'postal_code', 'is_default'
    ];
}
