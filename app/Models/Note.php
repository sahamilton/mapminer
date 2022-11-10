<?php

namespace App\Models;

class Note extends Model
{
    // Add your validation rules here
    public static $rules = [
        'note' => 'required',
    ];
    // Don't forget to fill this array

    protected $fillable = ['note', 'user_id', 'created_at', 'updated_at',  'address_id'];
    protected $table = 'notes';

    /**
     * [writtenBy description]
     * 
     * @return [type] [description]
     */
    public function writtenBy()
    {
        return $this->belongsTo(User::class, 'user_id')->with('person');
    }
    /**
     * [relatesToLocation description]
     * 
     * @return [type] [description]
     */
    public function relatesToLocation()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    
    /**
     * [locationNotes description]
     * 
     * @param [type] $location [description]
     * 
     * @return [type]           [description]
     */
    public function locationNotes($location)
    {
        return $this->where('address_id', '=', $location);
    }
    /**
     * [scopeSearch description]
     * 
     * @param [type] $query  [description]
     * @param [type] $search [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('note', 'like', "%{$search}%");
    }
}
