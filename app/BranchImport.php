<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchImport extends Model
{
    public $table = 'branchesimport';

    public function servicelines(){
    	return $this->belongsToMany(ServiceLine::class,'branch_serviceline','branch_id');
    }

    
}
