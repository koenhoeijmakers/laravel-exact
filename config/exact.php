<?php

return [
    'enabled'       => env('EXACT_ENABLED', false), // We'll give you the control over the exact.
    'client_id'     => env('EXACT_CLIENT_ID'),      // Your exact client id.
    'client_secret' => env('EXACT_CLIENT_SECRET'),  // Your client secret.
    'division'      => env('EXACT_DIVISION'),       // Your division.
    'refresh_token' => env('EXACT_REFRESH_TOKEN'),  // Your refresh token.
    'base_url'      => env('EXACT_BASE_URL', 'https://start.exactonline.nl'),
];
