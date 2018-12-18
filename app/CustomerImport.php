<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerImport extends Imports
{
	public $requiredFields = [
            'businessname',
            'street',
            'city',
            'state',
            'zip',
            'lat',
            'lng',
            ];


}