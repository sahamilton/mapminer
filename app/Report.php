<?php

namespace App;
use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    
    
    public $fillable = ['report', 'description', 'details', 'job', 'export'];
    
    /**
     * [company description]
     * 
     * @return [type] [description]
     */
    public function company()
    {
        return $this->belongsToMany(Company::class, 'company_report', 'id', 'company_id');

    } 
    /**
     * [companyreport description]
     * 
     * @return [type] [description]
     */
    public function companyreport()
    {
        return $this->hasMany(CompanyReport::class, 'report_id');
    }
    /**
     * Distribution [description]
     * 
     * @return [type] [description]
     */
    public function distribution()
    {
        return $this->belongsToMany(User::class, 'report_distribution');
    }
    /**
     * RoleDistribution [description]
     * 
     * @return [type] [description]
     */
    public function roleDistribution()
    {
        return $this->belongsToMany(Role::class, 'report_distribution');
    }

    /**
     * AccountDistribution [description]
     * 
     * @return [type] [description]
     */
    public function companyDistribution()
    {
        return $this->belongsToMany(Company::class, 'report_distribution');
    }
    /*public function distribution(){
         return $this->hasManyThrough(Distribution::class, CompanyReport::class, 'company_id', 'company_report_id', 'id', 'id');
    }*/
    /**
     * [period description]
     * 
     * @return [type] [description]
     */
    public function period()
    {
        if (! $this->period_to) {
            $period['to'] = Carbon::now();
        } else {
            $period['to'] = $this->period_to;
        }
        $period['from'] = $this->period_from;
        return $period;
    }

   
}
