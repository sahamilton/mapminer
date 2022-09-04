<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebLeadImport extends Imports
{
    public $requiredFields = [
            'businessname',
            'city',
            'state',
            'email',
            'phone',
            'firstname',
            'lastname',

            ];
    public $table = 'webleads';

    public function __construct()
    {
    }
}
