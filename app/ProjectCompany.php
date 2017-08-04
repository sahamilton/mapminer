<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCompany extends Model
{
    
	public $table ="projectcompanies";
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
           ];
	
    public function projects(){
    	return $this->belongsToMany(Project::class,'project_company_contact','company_id','project_id')->withPivot('type','contact_id');
    }

    public function employee(){
    	return $this->hasMany(ProjectContact::class,'projectcompany_id','id')->first();
    }
    

    public function projectemployee($project_id = '16694',$company_id='32539'){
     /* $contact = $this->projects()->with('contacts','companies')
        ->whereHas('companies',function($q) use($company_id){
          $q->where('projectcompanies.id','=',$company_id);
        })
        ->whereHas('contacts',function($q) use ($company_id){
            $q->where('projectcompany_id','=',$company_id);
        })

        ->wherePivot('company_id','=',$company_id)->find($project_id) ;
        dd($contact);*/
    }
}
