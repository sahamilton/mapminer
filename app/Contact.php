<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model 
{
    protected $fillable=['id','fullname','title','email','phone','comments','location_id','user_id','address_id'];


    public function location(){
    	return $this->belongsTo(Address::class,'address_id','id');
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function fullName(){
    	return $this->firstname . " " . $this->lastname;
    }
    public function relatedActivities(){
        return $this->belongsToMany(Activity::class,'activity_contact');
    }
}