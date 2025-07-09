<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'reseller_id', 'amount', 'status', 'requested_at', 'processed_at',
        'bank_name', 'account_number', 'account_holder_name'
    ];
}
