<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public $table = 'address_branch';

    public function periodOrders($branches = null){
    	$orders = $this->groupBy('branch_id')
   					->selectRaw('sum(orders) as sum, branch_id');
   			if($branches){
   				$orders->whereIn('branch_id',$branches);
   			}
   			return $orders->with('branches','branches.manager')->get();

    }

    public function branches(){
    	return $this->belongsTo(Branch::class,'branch_id','id');
    }
    public function addresses(){
    	return $this->belongsTo(Address::class,'address_id','id')->where('addressable_id','=','customer');
    }
    
    public function scopeBranchOrders($query,$branch){
        return $query->groupBy('address_id')
            ->selectRaw('sum(orders) as sum, address_id');
    }

    
}
