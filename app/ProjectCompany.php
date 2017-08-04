<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCompany extends Model
{
    
	public $table ="projectcompany";
	public $fillable =['factor_type',
           'firm',
           'contact',
           'title',
           'addr1',
           'addr2',
           'city',
           'state',
           'zipcode',
           'county',
           'phone',
           ]
	
    public function projects(){
    	return $this->belongsToMany(Project::class,'project_company_contact','projectcompany_id','project_id')->withPivot('type','contact_id');
    }
    public function employee(){
    	return $this->hasMany(ProjectContact::class,'id','projectcompany_id');
    }
}
