<?php
namespace App;
class Howtofield extends Model {

	// Add your validation rules here
	
	// Don't forget to fill this array
	protected $fillable = ['fieldname','required','type','values','group','sequence'];

	public function usedBy() {
		
		return belongsToMany(Company::class);	
	
	}



	
}