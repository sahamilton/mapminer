<?php

namespace App;

class State extends Model
{
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    public function getStates()
    {
        return $this->all()->pluck('fullstate', 'statecode')->toarray();
    }

    // Don't forget to fill this array
    protected $fillable = [];

    /**
     * [branches description].
     *
     * @return [type] [description]
     */
    public function branches()
    {
        return $this->hasMany(Branch::class, 'state', 'statecode');
    }

    /**
     * [locations description].
     *
     * @return [type] [description]
     */
    public function locations()
    {
        return $this->hasMany(Location::class, 'statecode', 'state');
    }

    /**
     * [addresses description].
     *
     * @return [type] [description]
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'statecode', 'state');
    }

    /**
     * [people description].
     *
     * @return [type] [description]
     */
    public function people()
    {
        return $this->hasMany(Person::class, 'statecode', 'state');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Salesactivity::class);
    }
}
