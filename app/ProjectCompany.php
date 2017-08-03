<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCompany extends Model
{
    
	public $table ="projectcompany";
    public function projects(){
    	return $this->belongsToMany(Projects::class);
    }
}
