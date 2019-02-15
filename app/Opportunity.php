<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    
    public $fillable = ['address_id','branch_id','address_branch_id','value','requirements','client_id','closed','top50','title','duration'];
    
    public function branch(){
    	return $this->belongsTo(AddressBranch::class,'address_branch_id')->with('branch');
    }

    public function address(){
    	return $this->belongsTo(AddressBranch::class,'address_branch_id')->with('address');
    }

    public function daysOpen(){
    	if($this->created_at){
    		return $this->created_at->diffInDays();
    	}
    	return null;
    }

    public function closed()
    {
        return $this->where('closed','=',2);
    }

    public function open()
    {
        return $this->where('closed','<>',2);
    }

    
}
