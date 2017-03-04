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

    public function assignedRoles()
    {
        return $this->belongsToMany(User::class);
    }

    public function assignRoles($role)
    {
    	return $this->roles()->save(
    		Role::whereName($role)->firstOrFail()
    		);

    }
}
