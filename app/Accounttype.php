<?php

namespace App;
class Accounttype extends Model {

	// Add your validation rules here
	public static $rules = [
		 'type' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];
	
	public function companies() {
		
		return $this->hasMany(Company::class,'accounttypes_id');
	}
}