<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model 
{
    protected $fillable=['id','firstname','lastname','title','email','phone','comments','location_id','user_id'];


    public function location(){
    	return $this->belongsTo(Location::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function fullName(){
    	return $this->firstname . " " . $this->lastname;
    }
    
}
