<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'price','product_media_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function cart(){
        return $this->hasMany(Cart::class);
    }
    public function media()
    {
        return $this->belongsTo(ProductMedia::class, 'product_media_id');
    }
}
