<?php

/**
 * Configuration file for Laravel Kickbox.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Basic settings
    |--------------------------------------------------------------------------
    |
    */
    'base_url'        => 'https://api.kickbox.com/v2/',
    'api_key'         => '',


    /*
    |--------------------------------------------------------------------------
    | Request settings
    |--------------------------------------------------------------------------
    |
    */
    'request' => [
        'timeout'    => 6000,   // Maximum request timeout in milliseconds
        'retry'      => 1,      // Number of attempts to retry in case of error (null = no retry).
        'retry_wait' => 200,    // Time to wait until next retry in milliseconds.
    ],


    /*
    |--------------------------------------------------------------------------
    | Cache settings
    |--------------------------------------------------------------------------
    |
    */
    'cache' => [
        'time'  => null,       // Cache time in seconds (null = no cache).
        'store' => 'default',  // Cache store to use.
        'prefix'=> 'Kickbox:', // Cache prefix.
    ]

];
