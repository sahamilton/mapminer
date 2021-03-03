<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchLeadImport extends Model
{
    public $table = 'branchleadsimport';
    public $nullFields = ['address2', 'phone', 'fax'];
    public $requiredFields = ['id', 'businessname', 'street', 'city', 'state', 'zip', 'contact', 'phone'];
}
