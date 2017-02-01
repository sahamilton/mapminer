<?php
namespace App;
class SalesOrg extends Eloquent {

	// Add your validation rules here
	public static $rules = [
		'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['title','name'];

	public function SalesOrgRole()
	{
		return $this->hasMany(Person::class,'position')
	}

}