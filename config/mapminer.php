<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credits
    |--------------------------------------------------------------------------
    |
    | 
    |
    */
    'author' => 'Stephen Hamilton',
    'developer' => 'Mapminer Development Corp, LLC',
    'developer_email' => 'support@mapminer.co',
    'website' => 'https://www.Mapminer.co',
    'client' => '',


    /*
    |--------------------------------------------------------------------------
    | App Version
    |--------------------------------------------------------------------------
    |
    | 
    |
    */
    'app_version' => trim(exec('git tag')),



    /*
    |--------------------------------------------------------------------------
    | Default Address
    |--------------------------------------------------------------------------
    |
    | This option defines the default physical address that gets used when searching
    ! within Mapminer. Also used if no address is specified when create user.
    |
    */

    'default_address'=>'804 F St, Petaluma, CA 94952',
    'default_location'=>['lat'=>'39.8282','lng'=>'-98.5795'],
    /*
    |--------------------------------------------------------------------------
    | System Contact
    |--------------------------------------------------------------------------
    |
    | This option defines the default email address for sending emails. Also
    | used as the primary contact for support.
    |
    */

    'support'=>'Mapminer Support Operations',
    'system_contact'=>env('MAPMINER_CONTACT', 'support@Mapminer.co'),

    
    'timeframes'=>[
        'future'=>'Future',
        'nextWeek'=>'Next Week',
        'tomorrow'=>'Tomorrow',
        'today'=>'Today',
        'yesterday'=>'Yesterday',
        'thisWeek'=>'This Week',
       // 'thisWeekToDate'=>'This Week To Date',
        'lastWeek'=>'Last Week',
        'thisMonth'=>'This Month',
       // 'thisMonthToDate'=>'This Month To Date',
        'lastMonth'=>'Last Month',
        'thisQuarter'=>'This Quarter',
       // 'thisQuarterToDate'=>'This Quarter To Date',
        'lastQuarter'=>'Last Quarter',
        'lastSixMonths'=>'Last Six Months',
        'lastTwelveMonth'=>'Last Year',

    ],

    'topdog'=>env('TOP_DOG', 2280),

    /*
    |--------------------------------------------------------------------------
    |Valdis email domains
    |--------------------------------------------------------------------------
    |
    | Used to validate new users and inbound email.
    | This should be extracted from the tenant
    |
    */ 
     'valid_domains'=>[

        


     ],


     /* 
    |--------------------------------------------------------------------------
    | activity address
    |--------------------------------------------------------------------------
    |
    | addres for recoring emails as activity
    |
    */
    'activity_email_address' => env('ACTIVITY_EMAIL_ADDRESS'),

      /*
     /*
    |--------------------------------------------------------------------------
    | Old Configs
    |--------------------------------------------------------------------------
    |
    | These options defines the default mapminer settings.
    |
    */



    'available_language' => ['en'],
    
    'search_radius'=>['2'=>'2','5'=>'5','10'=>'10','25'=>'25','50'=>'50','75'=>'75','100'=>'100','250'=>'250'],
    
    'zoom_levels'=>['2'=>'13','5'=>'12','10'=>'11','25'=>'10','50'=>'9','75'=>'8','100'=>'8','250'=>'6'],
   
    'default_radius'=>['10'=>'10'],
    
    'mysql_data_loc'=>app_path() .'/storage/uploads/',
    
    'api_key' => env('GOOGLE_MAPS_API_KEY'),
    
    'location_limit'=>env('LOCATION_LIMIT', 2000),

    'default_lat'=>'39.8282',
    'default_lng'=>'-98.5795',

    'fontawesome'=>env('FONTAWESOME_KIT'),
];
