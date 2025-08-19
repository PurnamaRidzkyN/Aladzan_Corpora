<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSubscription extends Model
{
    protected $fillable = ['reseller_id', 'plan_id', 'price', 'discount_code', 'discount_amount', 'payment_method', 'status', 'payment_proof', 'paid_at'];

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
