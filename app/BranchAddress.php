<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchAddress extends Model
{
    protected $table = 'address_branch';
    protected $increments = false;
    protected $fillable = ['branch_id', 'address_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
