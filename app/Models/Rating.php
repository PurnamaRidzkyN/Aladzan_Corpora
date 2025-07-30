<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['product_id','order_id' ,'reseller_id', 'rating', 'admin_id', 'comment', 'reply','reply_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
 
    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
