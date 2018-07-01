<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebLeadImport extends Imports
{
	public $requiredFields = [
            'companyname',
            'city',
            'state',
            'contactemail',
            'contactphone',
            'first_name',
            'last_name',
            'time_frame',
            
            ];

	public function __construct(){

	}
}