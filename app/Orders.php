<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public $table = 'orders';


    public function branch(){
    	return $this->belongsTo(Branch::class,'branch_id','id');
    }
    public function address(){
      return $this->belongsTo(AddressBranch::class,'address_branch_id','id')->with('address');
    	//return $this->hasManyThrough(Address::class,AddressBranch::class,'address_branch_id','address_id');
    }
    
    public function scopeBranchOrders($query,$branch){
        return $query->groupBy('address_id')
            ->selectRaw('sum(orders) as sum, address_id');
    }

    
}
