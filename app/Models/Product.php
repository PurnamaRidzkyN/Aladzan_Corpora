<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'shop_id'];

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->slug = static::generateUniqueSlug($product->name);
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

    public function shop()
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
    public function reviews()
    {
        return $this->hasMany(Rating::class);
    }
    public function rating()
    {
        return $this->hasOne(RatingSummary::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
   
}
