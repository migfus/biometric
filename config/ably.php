<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ably Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Ably real-time messaging and data synchronization.
    |
    */

    'key' => env('ABLY_API_KEY'),

    'client_id' => env('ABLY_CLIENT_ID', ''),

    'options' => [
        'useTokenAuth' => env('ABLY_USE_TOKEN_AUTH', false),
    ],
];
