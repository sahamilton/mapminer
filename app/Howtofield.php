<?php
namespace App;
class Howtofield extends Model {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['fieldname','required','type','values','group','sequence'];

	public function usedBy() {
		
		return belongsToMany('companies'Comapny::class);	
	
	}



	
}