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
    	return $this->hasMany(Person::class,'lead_person_status')->withPivot('status_id');
    }
    
    
}
