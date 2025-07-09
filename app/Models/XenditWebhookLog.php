<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XenditWebhookLog extends Model
{
    protected $fillable = [
        'payment_id', 'event_type', 'raw_payload', 'received_at'
    ];
}
