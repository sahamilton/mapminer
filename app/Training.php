<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    
	public $fillable =["title","description","reference","type","datefrom","dateto" ];

    public function relatedRoles(){
    	return $this->belongsToMany(Roles::class);
    }

    public function relatedIndustries(){
		return $this->belongsToMany(SearchFilter::class);
	}

	public function serviceline()
	{
		return $this->belongsToMany(Serviceline::class);
	}
}
