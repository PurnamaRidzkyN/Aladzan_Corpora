<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'file_path',
        'file_type',
        'original_name',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant(){
        return $this->hasOne(ProductVariant::class);
    }
}
