<?php

return [

    /**
     * Client ID.
     */
    'client_id' => env('GOOGLE_CLIENT_ID', '296049329457-icijm183dobbe0mucqc56mi70cldssa3.apps.googleusercontent.com'),

    /**
     * Client Secret.
     */
    'client_secret' => env('GOOGLE_CLIENT_SECRET', 'I7b4BG78vEMJ-P4WeN09heXu'),

    /**
     * Scopes.
     */
    'scopes' => [
        'https://www.googleapis.com/auth/youtube',
        'https://www.googleapis.com/auth/youtube.upload',
        'https://www.googleapis.com/auth/youtube.readonly'
    ],

    /**
     * Route URI's
     */
    'routes' => [

        /** 
         * Determine if the Routes should be disabled.
         * Note: We recommend this to be set to "false" immediately after authentication.
         */
        'enabled' => true,

        /**
         * The prefix for the below URI's
         */
        'prefix' => 'youtube',

        /**
         * Redirect URI
         */
        'redirect_uri' => 'callback',

        /**
         * The autentication URI
         */
        'authentication_uri' => 'auth',

        /**
         * The redirect back URI
         */
        'redirect_back_uri' => '/',

    ]

];
