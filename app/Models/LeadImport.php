<?php

namespace App\Models;

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
            'lng', ];

    public $temptable = 'leadimport';
    public $dontCreateTemp = true;
}
