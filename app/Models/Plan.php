<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'description', 'price', 'currency', 'duration_days'];

    public function orderSubscriptions()
    {
        return $this->hasMany(OrderSubscription::class);
    }
     public function resellers()
    {
        return $this->hasMany(Reseller::class);
    }

}
