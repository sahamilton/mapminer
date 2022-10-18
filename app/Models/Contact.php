<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $fillable=[
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
        'user_id'
        ];
        
    public $dates = ['created_at', 'updated_at'];
    /**
     * [location description]
     * 
     * @return [type] [description]
     */
    public function location()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function addressBranch()
    {
        return $this->belongsTo(AddressBranch::class, 'address_id', 'address_id');
    }
    /**
     * [user description]
     * 
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * [relatedActivities description]
     * 
     * @return [type] [description]
     */
    public function relatedActivities()
    {
        return $this->belongsToMany(Activity::class, 'activity_contact');
    }
    /**
     * [getMyContacts description]
     * 
     * @return [type] [description]
     */
    public function getMyContacts()
    {
        return $this->with('location')->where('user_id', '=', auth()->user()->id)->get();
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
        return  $query->where('firstname', 'like', "%{$search}%")
            ->Orwhere('lastname', 'like', "%{$search}%")
            ->Orwhere('fullname', 'like', "%{$search}%");

    }
    /**
     * [getPhoneNumberAttribute description]
     * 
     * @return [type] [description]
     */
    public function getPhoneNumberAttribute()
    {
        if ($this->contactphone) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $this->contactphone);
            if (preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches)) {
                return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
            }
            return $this->contactphone;
        }
        return null;
        
    }
    /**
     * [getFullEmailAttribute description]
     * 
     * @return [type] [description]
     */
    public function getFullEmailAttribute()
    {
        if ($this->email) {
             return "<a href='mailto:".$this->completeName ." <".$this->email.">?bcc=".config('mapminer.activity_email_address')."' target='_blank'>".$this->email."</a>";
             
        }
        return null;
        
    }
    /**
     * [getCompleteNameAttribute description]
     * 
     * @return [type] [description]
     */
    public function getCompleteNameAttribute()
    {
        if (! $this->fullname) {
            return $this->firstname . ' ' .$this->lastname;
        } else {
            return $this->fullname;
        }
    }

    
}
