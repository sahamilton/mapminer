<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    
    public $fillable = ['address_id','branch_id','value','requirements'];
    
    public function branch(){
    	return $this->belongsTo(Branch::class);
    }

    public function address(){
    	return $this->belongsTo(Address::class);
    }

    public function daysOpen(){
    	if($this->created_at){
    		return $this->created_at->diffInDays();
    	}
    	return null;
    }

    public function activities(){
        return $this->hasMany(Activity::class);
    }

    


}
