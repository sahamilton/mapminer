<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $fillable = ['type','test','route','message','created_by','expiration'];
    public $dates =['expiration'];
    /**
     * [participants description]
     * 
     * @return [type] [description]
     */
    public function participants()
    {
        return $this->belongsToMany(Person::class)->withPivot('activity');
    }
    /**
     * [respondents description]
     * 
     * @return [type] [description]
     */
    public function respondents()
    {
        return $this->belongsToMany(Person::class)->withPivot('activity')->wherePivot('activity', '!=', 'null');
    }
    /**
     * [author description]
     * 
     * @return [type] [description]
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
