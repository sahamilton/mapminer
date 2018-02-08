<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectContact extends Model
{
   public $table='projectcontacts';
   public $fillable = ['id','contact','company_id','title','contactphone','email'];
    public function projects(){
    	return $this->belongsToMany(Project::class,'project_company_contact','projectcontact_id','project_id')->withPivot('type','projectcompany_id');
    }

    public function employer(){
    	return $this->belongsTo(ProjectCompany::class,'company_id','id');
    }
}