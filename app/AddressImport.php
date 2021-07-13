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
            'lng',
            'branch_id' ];

    public $temptable = 'address_import';
    public $dontCreateTemp = true;
}
