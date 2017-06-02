<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesLead extends Model
{
    public $table = 'leads';
    public $dates = ['created_at','updated_at','datefrom','dateto'];
    public $ownedStatus = 2;    
    public $assignedStatus = 1;

    
    public function assignedTo(){
    	return $this->hasMany(Person::class,'lead_person_status')->where('status_id','=',$this->assignedStatus)->where('person.user_id','=',auth()->user()->id);
    }
    public function ownedBy(){
    	return $this->hasMany(Person::class,'lead_person_status')->where('status_id','=',$this->ownedStatus)->where('person.user_id','=',auth()->user()->id);
    }
}
