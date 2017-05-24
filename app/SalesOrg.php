<?php
namespace App;

class SalesOrg extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		'title' => 'required'
	];
	public $table = 'salesorgs';
	// Don't forget to fill this array
	protected $fillable = ['title','name'];

	public function SalesOrgRole()
	{
		return $this->hasMany(Person::class,'position');
	}

	public function getSalesOrg(){

		return Person::with('userdetails','userdetails.roles','userdetails.serviceline','industryfocus')
		->whereHas('userdetails.roles',function($q){
    		$q->where('name','=','Sales');
		})
		->whereNotNull('lat')
		->get();
	}

}