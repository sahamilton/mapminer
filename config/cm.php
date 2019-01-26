<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | You can find your API key on your Construction Monitor dashboard.
    |
    | This api key points the Construction Monitor notifier to the project in your account
    | which should receive your application's uncaught exceptions.
    |
    */

    'api_key' => env('CM_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | App Type
    |--------------------------------------------------------------------------
    |
    | Set the type of application executing the current code.
    |
    */

    'app_type' => env('CM_APP_TYPE'),

    /*
    |--------------------------------------------------------------------------
    | App Version
    |--------------------------------------------------------------------------
    |
    | Set the version of application executing the current code.
    |
    */

    'app_version' => env('CM_APP_VERSION','v1'),

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    |
    |
    */
    'app_user' =>env('CM_USER'),

    /*
    |--------------------------------------------------------------------------
    | End Point
    |--------------------------------------------------------------------------
    |
    |
    */
    'app_endpoint'=>env('CM_URL','https://api.constructionmonitor.com/v1/'),
];
