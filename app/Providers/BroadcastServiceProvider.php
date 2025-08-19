<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Middleware untuk route broadcast auth
        Broadcast::routes(['middleware' => ['auth:admin']]);

        require base_path('routes/channels.php');
    }
}
