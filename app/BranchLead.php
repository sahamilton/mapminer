<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchLead extends Model
{
    public $table = 'branch_lead';

    public $fillable = ['branch_id','lead_id','address_id'];

    public function address(){
    	return $this->belongsTo(Address::class);
    }
}
