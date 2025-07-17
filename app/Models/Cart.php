<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'reseller_id', 'product_variant_id', 'quantity'
    ];
    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
