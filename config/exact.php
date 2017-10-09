<?php

return [
    'enabled'       => env('EXACT_ENABLED', false), // We'll give you the control over the exact.
    'client_id'     => env('EXACT_CLIENT_ID'),      // Your exact client id.
    'client_secret' => env('EXACT_CLIENT_SECRET'),  // Your client secret.
    'division'      => env('EXACT_DIVISION'),       // Your division.
    'access_token'  => env('EXACT_ACCESS_TOKEN'),   // Your access token. (if not supplied we'll do the oAuth dance).
    'refresh_token' => env('EXACT_REFRESH_TOKEN'),  // Your refresh token. (if not supplied we'll do the oAuth dance).
];
