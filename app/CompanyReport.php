<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyReport extends Model
{
    public $table = 'company_report';

    public function company(){
    	return $this->belongsTo(Company::class);
    }

    public function distribution(){
    	return $this->belongsToMany(User::class,'report_distribution')->withPivot('type');
    }
}
