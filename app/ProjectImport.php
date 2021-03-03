<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectImport extends Imports
{
    public $requiredFields = ['id', 'project_title', 'lat', 'lng', 'street', 'city', 'state', 'zipcode'];
    public $table = 'projectsimport';
}
