<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $fillable = ['name', 'display_name'];

    /**
     * [roles description].
     *
     * @return [type] [description]
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function scopeSearch($query, $search)
    {
        $query->where('display_name',  'like', "%{$search}%");
    }
}
