<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressBranch extends Model
{
    public $table = 'address_branch';

    public function orders(){
    	return $this->hasMany(Orders::class,'id', 'address_branch_id');
    }
    public function branch(){
    	return $this->belongsTo(Branch::class, 'branch_id','id');
    }
     public function address(){
        return $this->belongsTo(Address::class, 'address_id','id');
    }
}
