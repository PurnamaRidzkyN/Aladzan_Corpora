<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebRating extends Model
{
    protected $fillable = ['reseller_id', 'rating', 'comment'];



    public function reseller() {
        return $this->belongsTo(reseller::class);
    }
}