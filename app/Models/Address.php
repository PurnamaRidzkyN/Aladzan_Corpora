<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'reseller_id',
        'recipient_name',
        'phone_number',
        'province',
        'city',
        'district',
        'village',
        'neighborhood',
        'hamlet',
        'sub_district',
        'zipcode',
        'address_detail',
        'sub_district_id',
    ];

    // Relasi ke Reseller
    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }
}
