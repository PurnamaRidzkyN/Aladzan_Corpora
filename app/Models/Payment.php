<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['reseller_id', 'external_id', 'amount', 'status', 'payment_method', 'voucher_id', 'paid_at'];

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    
}
