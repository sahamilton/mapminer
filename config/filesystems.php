<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "public", sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path(),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'reports' => [
            'driver' => 'local',
            'root' => storage_path('app/public/reports'),
            'url' => env('APP_URL').'/storage/reports',
            'visibility' => 'public',
        ],
        'documents' => [
            'driver' => 'local',
            'root' => storage_path('app/public/documents'),
            'url' => env('APP_URL').'/storage/documents',
            'visibility' => 'public',
        ],
        'avatars' => [
            'driver' => 'local',
            'root' => storage_path('app/public/avatars'),
            'url' => env('APP_URL').'/storage/avatars',
            'visibility' => 'public',
        ],

        'imports' => [
            'driver' => 'local',
            'root' => storage_path('app/public/imports'),
            'url' => env('APP_URL').'/storage/imports',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'dropbox' => [
            'driver' => 'dropbox',
            'authorization_token'=>env('DROPBOX_TOKEN')
        ],
        'sftp' => [
            'driver' => 'sftp',
            'host' => env('sftp_host'),
            'username' => 'forge',
            // Settings for SSH key based authentication...
            'privateKey' => env('sftp_key'),
            'password' => env('sftp_key_pwd', ''),
            'visibility' => 'public',
            'permPublic' => 0766, /// <- this one did the trick

        ],

    ],

];


