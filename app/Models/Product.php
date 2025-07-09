<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'group_id'];

    public function shops()
    {
        return $this->belongsTo(Shop::class, 'group_id');
    }
      public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
