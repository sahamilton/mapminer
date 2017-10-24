<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchImport extends Imports
{
    public $table = 'branchesimport';

    public $requiredFields = ['id','branchname'];

    public function __construct(){
    	
    }
    public function servicelines(){
    	return $this->belongsToMany(ServiceLine::class,'branch_serviceline','branch_id');
    }

    
}