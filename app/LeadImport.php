<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadImport extends Imports
{
	public $requiredFields = ['companyname',
            'businessname',
            'address',
            'city',
            'state',
            'zip',
            'lat',
            'lng'];

	public function __construct(){

	}
}