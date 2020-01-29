<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressImport extends Imports
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
