<?php
namespace App;

class SalesOrg extends \Eloquent {

	use Geocode;

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

	// get sales reps who have a geocode
	public function getSalesOrg(){

		return Person::with('userdetails','userdetails.roles','userdetails.serviceline','industryfocus')
		->whereHas('userdetails.roles',function($q){
    		$q->where('id','=','5');
		})
		->whereNotNull('lat')
		->get();
	}
	// Identify people who have sales rep role
	// but are not in the sales organization
	// hierarchy
	public function salesRepsOutsideOrg(){
		$topDog = Person::findOrFail(1767);
		$salesReps = $topDog->allLeaves()->salesReps()->pluck('id')->toArray();

		$salesRoles = Person::salesReps()->pluck('id')->toArray();

		$diff = array();
		
		$diff['insiders'] = array_diff($salesRoles,$salesReps);
		return $diff;
	}

}