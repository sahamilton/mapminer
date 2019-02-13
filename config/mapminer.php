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
	'system_contact'=>env('MAPMINER_CONTACT','salesoperations@trueblue.com')

];