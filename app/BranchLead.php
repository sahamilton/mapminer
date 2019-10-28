<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchLead extends Model
{
    public $table = 'address_branch';

    public $fillable = ['status_id', 'comments', 'address_id', 'branch_id'];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class, 'address_id', 'address_id');
    }
}
