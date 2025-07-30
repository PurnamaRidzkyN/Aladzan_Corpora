<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'code','amount', 'is_percent', 'valid_until'
    ];
}
