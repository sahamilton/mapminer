<?php

return [
	/*
	|---------------------------------------------------------------------------
	|
	|
	|
	|
	 */
	
	'test'=>env('LEADS_TEST',false),
 	/*
    |--------------------------------------------------------------------------
    |Ownedlimit
    |--------------------------------------------------------------------------
    |
    | Here you may specify maximum number of leads that can be owned.
    |
    */

	'owned_limit'=> env("OWNED_LEADS_LIMIT",5),

 	/*
    |--------------------------------------------------------------------------
    | Search Radius
    |--------------------------------------------------------------------------
    |
    | Here you may specify radius of the search forr lead assignement.
    |
    */

	'search_radius'=>env("LEADS_SEARCH_RADIUS",100),
	
	/*
    |--------------------------------------------------------------------------
    | Lead Distribution Roles
    |--------------------------------------------------------------------------
    |
    | Here you may specify user roles that can accept leads.
    |
    */

	'lead_distribution_roles'=>(['Sales','Sales Representative']),


    'test'=>env("LEADS_TEST",false),

];