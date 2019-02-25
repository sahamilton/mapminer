<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCompany extends Model
{
  
    public $table ="projectcompanies";
    public $incrementing = false;
    public $fillable =['factor_type',
           'firm',
           'contact',
           'title',
           'addr1',
           'addr2',
           'city',
           'state',
           'zip',
           'county',
           'phone',
           ];
    
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_company_contact', 'company_id', 'project_id')->withPivot('type', 'contact_id');
    }

    public function employee()
    {
        return $this->hasMany(ProjectContact::class, 'company_id', 'id');
    }
}
