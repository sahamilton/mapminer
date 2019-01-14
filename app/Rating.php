<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public $table = 'address_person';

    public function myRatings(){
    	return $this->where('person_id','=',auth()->user()->person->id)->with('address');
    }

    public function address(){
    	return $this->belongsTo(Address::class);
    }
}
