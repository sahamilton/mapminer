<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactImport extends Imports
{
    use Geocode;
    public $requiredFields = [
                'firstname',
                'lastname',
                'contactphone',
                'email',
                'fullname',
                'title',
                'businessname',
                'street',
                'city',
                'state',
                'zip',
                'lat',
                'lng',
                'company_id'
            ];
    public $fillable = [
            'address_id',
            'user_id',
            'lead_source_id'
        ];
    public $table = 'contacts_import';
    public $tempTable = 'contacts_import'; 
    public $dontCreateTemp= true;

    public function address() 
    {
        return $this->belongsTo(Address::class);
    }

    public function company() 
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('firstname', 'like', "%{$search}%")
            ->orWhere('lastname', 'like', "%{$search}%")
            ->orWhere('title', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('contactphone', 'like', "%{$search}%")
            ->orWhere('street', 'like', "%{$search}%")
            ->orWhere('city', 'like', "%{$search}%")
            ->orWhere('state', 'like', "%{$search}%")
            ->orWhere('zip', 'like', "%{$search}%")
            ->orWhere('businessname', 'like', "%{$search}%");
    }

    public function fullName() 
    {
        return $this->firstname . " " .$this->lastname;
    }
}
