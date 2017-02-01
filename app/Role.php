<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function permissions()
    {
    	return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(Permission $permission)
    {
    	return $this->permissions()->save($permission);
    }

    public function assignRole($role)
    {
    	return $this->roles()->save(
    		Role::whereName($role)->firstOrFail()
    		);

    }
}
