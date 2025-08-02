<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_code', 'reseller_id', 'total_price', 'status', 'shipping_address', 'note', 'total_shipping', 'is_paid_at', 'is_processed_at', 'is_shipped_at', 'is_done_at', 'is_cancelled_at', 'payment_proofs', 'payment_method'];

    protected $appends = ['status_name'];
    public function getStatusNameAttribute()
    {
        $labels = [
            0 => 'Belum Dibayar',
            1 => 'Diproses',
            2 => 'Dikirim',
            3 => 'Selesai',
            4 => 'Dibatalkan',
        ];
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            4 => 'error', // Cancel
            3 => 'success', // Selesai
            2 => 'info', // Dikirim
            1 => 'warning', // Diproses
            0 => 'secondary', // Belum dibayar
            default => 'secondary',
        };
    }

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
}
