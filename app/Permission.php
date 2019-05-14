<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $fillable = ['name','display_name'];
    /**
     * [roles description]
     * 
     * @return [type] [description]
     */
    public function roles()
    {
        
            return $this->belongsToMany(Role::class);
    }
}
