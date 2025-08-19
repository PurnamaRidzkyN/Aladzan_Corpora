<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('shop.{shopId}', function ($user, $shopId) {
    Log::info("User ID {$user->id} joined channel shop.{$shopId}");
    return $user != null;
});

