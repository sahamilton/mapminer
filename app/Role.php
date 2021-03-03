<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $fillable = ['name'];

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
            self::whereName($role)->firstOrFail()
        );
    }

    public function getCurrentPermissions(self $role)
    {
        $permissions = $this->whereId($role->id)->with('permissions')->first();
        $currentPermissions = [];
        foreach ($permissions->permissions as $permission) {
            $currentPermissions[] = $permission->id;
        }

        return $currentPermissions;
    }
}
