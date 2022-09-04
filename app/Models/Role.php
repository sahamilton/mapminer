<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $fillable = ['name', 'display_name'];

    public array $salesRoles =[3, 6,7,9,14];
    public array $adminRoles =[1,12];

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

    public function scopeSearch($query, $search)
    {
        $query->where('display_name',  'like', "%{$search}%");
    }

    public function scopeType($query, $type)
    {
        switch($type) {
        case 'sales':
            return $query->whereIn('id', $this->salesRoles);

            break;

        case 'system':
            return $query->whereIn('id', $this->adminRoles);
            break;

        default:
            return $query;


        }
    }
}
