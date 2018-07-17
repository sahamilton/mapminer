<?php

namespace App;



class Training extends Model
{
    
	public $fillable =["title","description","reference","type","datefrom","dateto" ];

    public function relatedRoles(){
    	return $this->belongsToMany(Role::class);
    }

    public function relatedIndustries(){
		return $this->belongsToMany(SearchFilter::class,'searchfilter_training','training_id','searchfilter_id');
	}

	public function servicelines()
	{
		return $this->belongsToMany(Serviceline::class);
	}
}
