<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressBranch extends Model
{
    public $table = 'address_branch';
    public $fillable = ['branch_id','address_id'];
    public function orders()
    {
        return $this->hasMany(Orders::class, 'id', 'address_branch_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    public function activities()
    {
        return $this->hasMany(Activity::class, 'address_id', 'address_id');
    }
    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'address_branch_id', 'id');
    }
}
