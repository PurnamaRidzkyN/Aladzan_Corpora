<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | Set broadcaster default. Karena kamu nggak pakai realtime,
    | paling aman pakai "log".
    |
    */

    'default' => env('BROADCAST_CONNECTION', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Cukup sisakan koneksi minimal saja (log & null).
    |
    */

    'connections' => [

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
