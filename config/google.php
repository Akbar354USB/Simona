<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google OAuth Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini digunakan untuk OAuth Google (Google Calendar)
    |
    */

    'client_id' => env('GOOGLE_CLIENT_ID'),

    'client_secret' => env('GOOGLE_CLIENT_SECRET'),

    'redirect_uri' => env('GOOGLE_REDIRECT_URI'),

    'scopes' => explode(',', env('GOOGLE_SCOPES', 'https://www.googleapis.com/auth/calendar')),

];
