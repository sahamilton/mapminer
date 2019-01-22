<?php
namespace App;
class Region extends Model {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['region'];
	
	public function branches() 
	{
		return $this->hasMany(Branch::class);	
	}
	
	
	
}