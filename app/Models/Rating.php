<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(Reseller::class);
    }
 
    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }
}
