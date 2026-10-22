<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Zoom Server-to-Server OAuth Credentials
    |--------------------------------------------------------------------------
    | Account ID, Client ID and Client Secret from your Zoom Marketplace App.
    | Type: Server-to-Server OAuth
    */

    'account_id'         => env('ZOOM_ACCOUNT_ID'),
    'client_id'          => env('ZOOM_CLIENT_ID'),
    'client_secret'      => env('ZOOM_CLIENT_SECRET'),
    'verification_token' => env('ZOOM_VERIFICATION_TOKEN'),
    'base_url'           => 'https://api.zoom.us/v2',
    'token_url'          => 'https://zoom.us/oauth/token',

];
