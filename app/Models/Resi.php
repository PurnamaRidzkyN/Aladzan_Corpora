<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resi extends Model
{
    protected $fillable = ['resi_number', 'file_path', 'resi_source_id', 'file_name'];

    public function resiSource()
    {
        return $this->belongsTo(ResiSource::class);
    }
    public function orders()
    {
        return $this->hasOne(Order::class);
    }
}