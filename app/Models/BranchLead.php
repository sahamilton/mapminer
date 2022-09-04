<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchLead extends Model
{
    public $table = 'address_branch';

    public $fillable = ['status_id', 'comments', 'address_id', 'branch_id', 'person_id'];
    /**
     * [address description]
     * 
     * @return [type] [description]
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    /**
     * [branch description]
     * 
     * @return [type] [description]
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    /**
     * [activity description]
     * 
     * @return [type] [description]
     */
    public function activity()
    {
        return $this->hasMany(Activity::class, 'address_id', 'address_id');
    }
}
