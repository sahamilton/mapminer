<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchImport extends Imports
{
    public $table = 'branchesimport';

    public $requiredFields = ['id','branchname','street','city','state','zip','lat','lng'];

    
    public function servicelines(){
    	return $this->belongsToMany(ServiceLine::class,'branch_serviceline','branch_id');
    }

    public function branches(){
    	return hasOne(Branch::class,'id','id');
    }
}