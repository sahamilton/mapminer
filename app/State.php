<?php
namespace App;
class State extends Model {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];
	
	public function branches() 
	{
		return $this->hasMany(Branch::class,'statecode','state');	
	}
	
	public function locations() 
	{
		return $this->hasMany(Location::class,'statecode','state');	
	}
}