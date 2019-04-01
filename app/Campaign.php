<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $fillable = ['type','test','route','message','created_by','expiration'];
    public $dates =['expiration'];

    public function participants()
    {
        return $this->belongsToMany(Person::class)->withPivot('activity');
    }

    public function respondents()
    {
        return $this->belongsToMany(Person::class)->withPivot('activity')->wherePivot('activity', '!=', 'null');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
