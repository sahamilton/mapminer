<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonIndustry extends Model
{
    protected $table = 'person_search_filter';

    public function person(){
    	return $this->belongsTo(Person::class);
    }

    public function industry(){
    	return $this->belongsTo(SearchFilter::class,'search_filter_id','id');
    }
}
