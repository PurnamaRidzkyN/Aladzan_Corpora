<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'zipcode', 'city', 'sub_district_id', 'img_path', 'video_path'];
    protected static function booted()
    {
        static::creating(function ($shop) {
            $shop->slug = static::generateUniqueSlug($shop->name);
        });
        static::deleting(function ($shop) {
            if ($shop->isForceDeleting()) {
                // Kalau beneran dihapus permanent
                $shop->products()->forceDelete();
            } else {
                // Kalau soft delete
                $shop->products()->delete();
            }
        });

        static::restoring(function ($shop) {
            // Kalau shop direstore, restore semua product juga
            $shop->products()->restore();
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
