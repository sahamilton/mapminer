<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchLead extends Model
{
    public $table = 'addresse_branch';

    public $fillable = [];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
