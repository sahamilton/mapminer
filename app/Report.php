<?php

namespace App;
use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    
    
    public $fillable = ['report', 'description', 'details', 'job', 'export', 'public', 'filename'];
    
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
        return $this->belongsToMany(User::class, 'report_distribution')->withTimestamps();
    }
    /**
     * RoleDistribution [description]
     * 
     * @return [type] [description]
     */
    public function roleDistribution()
    {
        return $this->belongsToMany(Role::class, 'report_distribution')->withTimestamps();
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
    /**
     * [getDistribution description]
     * 
     * @return [type] [description]
     */
    public function getDistribution()
    {
       
        $distribution = $this->distribution->map(
            function ($user) {
                return ['email'=>$user->email, 'name'=>$user->person->fullName()];
            }
        );
     
        if ($distribution->count()==0) {
            $distribution = [['email'=>config('mapminer.system_contact'), 'name'=>'Unknown Recipient']];
        } 
      
        return $distribution;
    }
    /**
     * [scopePublicReports description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopePublicReports($query)
    {
        return $query->where('public', 1);
    }

}
