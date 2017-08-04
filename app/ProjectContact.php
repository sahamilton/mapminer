<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectContact extends Model
{
   public $table='projectcontacts';

    public function projects(){
    	return $this->belongsToMany(Project::class,'project_company_contact','projectcontact_id','project_id')->withPivot('type','projectcompany_id');
    }

    public function employer(){
    	return $this->belongsTo(ProjectCompany::class,'projectcompany_id','id');
    }
}
