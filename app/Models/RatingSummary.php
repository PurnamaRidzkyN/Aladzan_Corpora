<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/RatingSummary.php
class RatingSummary extends Model
{
    protected $table = 'rating_summary_view';
    public $timestamps = false;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
