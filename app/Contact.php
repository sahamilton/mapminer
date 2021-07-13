<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable=[
        'address_id',
        'contactphone',
        'comments',
        'email',
        'firstname',
        'lastname',
        'fullname',
        'location_id',
        'primary',
        'title',
        'user_id',
        'created_at'
        ];
        
    public $dates = ['created_at', 'updated_at'];

    public function location()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fullName()
    {
        if (isset($this->fullname)) {
            return $this->fullname;
        }
        return $this->firstname . " " . $this->lastname;
    }
    public function relatedActivities()
    {
        return $this->belongsToMany(Activity::class, 'activity_contact');
    }

    public function getMyContacts()
    {
        return $this->with('location')->where('user_id', '=', auth()->user()->id)->get();
    }

    public function scopeSearch($query, $search)
    {
        return  $query->where('firstname', 'like', "%{$search}%")
            ->Orwhere('lastname', 'like', "%{$search}%")
            ->Orwhere('fullname', 'like', "%{$search}%");

    }
}
