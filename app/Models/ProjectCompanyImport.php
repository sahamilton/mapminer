<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCompanyImport extends Imports
{
    public $requiredFields = ['project_id', 'firm', 'addr1', 'city', 'state', 'zip'];
    public $table = 'projectcompanyimport';
}
