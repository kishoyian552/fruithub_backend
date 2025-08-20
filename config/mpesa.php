<?php

return [

    /*
    |--------------------------------------------------------------------------
    | M-PESA Configuration
    |--------------------------------------------------------------------------
    |
    | These values are pulled from your .env file.
    | Make sure your .env contains the correct credentials.
    |
    */

    'consumer_key'    => env('MPESA_CONSUMER_KEY', ''),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),
    'shortcode'       => env('MPESA_SHORTCODE', '174379'),
    'passkey'         => env('MPESA_PASSKEY', ''),
    'env'             => env('MPESA_ENV', 'sandbox'), // set to 'production' when live

    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | Replace this with your Ngrok/LocalTunnel/public domain URL.
    | Example for your current LocalTunnel:
    |   https://eight-snakes-hug.loca.lt/api/mpesa/callback
    |
    */

    'callback_url'    => env('MPESA_CALLBACK_URL', 'https://eight-snakes-hug.loca.lt/api/mpesa/callback'),

];
