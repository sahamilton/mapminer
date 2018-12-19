<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model 
{
    protected $fillable=['id','contact','title','email','phone','comments','location_id','user_id'];


    public function location(){
    	return $this->belongsTo(Address::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function fullName(){
    	return $this->firstname . " " . $this->lastname;
    }
    
}
