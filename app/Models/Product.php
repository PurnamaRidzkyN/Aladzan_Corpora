<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price',  'shop_id'];

    public function shops()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
      public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function media()
{
    return $this->hasMany(ProductMedia::class);
}
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
