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
            'firstname',
            'lastname',
            'time_frame',
            
            ];
      public $table = 'webleads';
	public function __construct(){

	}
}