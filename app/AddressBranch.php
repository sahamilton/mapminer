<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressBranch extends Model
{
    public $table = 'address_branch';

    public function orders(){
    	return $this->hasMany(Orders::class,'id', 'orders_id');
    }
    public function branch(){
    	return $this->hasMany(Branch::class,'id', 'branch_id');
    }
}
