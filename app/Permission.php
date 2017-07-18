<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $fillable = ['name','display_name'];
    
    public function roles()
    {
    	return $this->belongsToMany(Role::class);
    }
}
