<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'product_variant_id', 'product_name', 'quantity', 'price_each'
    ];
    public function variant(){
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
}

