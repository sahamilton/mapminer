<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectContact extends Model
{
   public $table='projectcontacts';
   public $fillable = ['contact','company_id','title','contactphone','email'];
    public function projects(){

    }
    public function employer(){
    	return $this->belongsTo(ProjectCompany::class,'company_id','id');
    }
}