<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['name', 'description','zipcode','city','sub_district_id'];
   protected static function booted()
    {
        static::creating(function ($shop) {
            $shop->slug = static::generateUniqueSlug($shop->name);
        });
    }

     protected static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'shop_id');
    }
}
