<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public $table = 'orders';

    public function periodOrders($branches = null){
    	$orders = $this->groupBy('branch_id')
   					->selectRaw('sum(orders) as sum, branch_id');
   			if($branches){
   				$orders->whereIn('branch_id',$branches);
   			}
   			return $orders->with('branches','branches.manager','addresses')->get();

    }


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
