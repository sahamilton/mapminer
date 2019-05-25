<?php

namespace App;

class AccountType extends Model
{

    // Add your validation rules here
    public $table = 'accounttypes';
    public static $rules = [
         'type' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = [];
    /**
     * [companies description]
     * 
     * @return [type] [description]
     */
    public function companies()
    {
        
        return $this->hasMany(Company::class, 'accounttypes_id');
    }
}
