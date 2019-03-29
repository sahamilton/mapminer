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
    'developer' => 'Okos Partners, LLC',
    'developer_email' => 'hamilton@okospartners.com',
    'website' => 'https://www.OkosPartners.com',
    'client' => 'TrueBlue, Inc.',


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

    'default_address'=>'1015 A St, Tacoma, WA 98402',
    
    /*
    |--------------------------------------------------------------------------
    | System Contact
    |--------------------------------------------------------------------------
    |
    | This option defines the default email address for sending emails. Also
    | used as the primary contact for support.
    |
    */

    'support'=>'Sales Operations',
    'system_contact'=>env('MAPMINER_CONTACT', 'salesoperations@trueblue.com'),

    
    'timeframes'=>[
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

    ],


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
    
    'api_key' => env('GOOGLE_MAPS_API_KEY', 
        'AIzaSyCzMYQy-JSMClnXr82HGmVp3YHYzZUOTTg'),
    
    'location_limit'=>env('LOCATION_LIMIT', 2000),




];
