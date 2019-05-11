<?php

namespace App;
use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	public $dates = ['period_from','period_to'];
    // Distribution
    // many through
    // 
    
    public function company(){
    	return $this->belongsToMany(Company::class,'company_report','id','company_id');

    } 
    // //
    // 
    public function companyreport(){
    	return $this->hasMany(CompanyReport::class,'report_id');
    }
    /*public function distribution(){
    	 return $this->hasManyThrough(Distribution::class, CompanyReport::class, 'company_id', 'company_report_id', 'id', 'id');
    }*/
	/**
	 * Generate period for report
	 * @return array [period to & period from]
	 */
    public function period()
    {
    	if(! $this->period_to){
    		$period['to'] = Carbon::now();
    	}else{
    		$period['to'] = $this->period_to;
    	}
    	$period['from'] = $this->period_from;
    	return $period;
    }

   
}
