<?php
namespace App;
class Person extends NodeModel {

	// Add your validation rules here
	public static $rules = [
	'email'=>'required',
	'mgrtype' => 'required'
	];
	protected $table ='persons';

	protected $parentColumn = 'reports_to';


	// Don't forget to fill this array
	public $fillable = ['firstname','lastname','email','phone','address','lat','lng','reports_to'];

	public function reportsTo()
    {
        return $this->belongsTo(Person::class, 'reports_to');
    }

    public function directReports()
    {
        return $this->hasMany(Person::class, 'reports_to');
    }
	public function salesRole()
	{
		return $this->belongsTo(SalesOrg::class,'id','position');
	}
	
	public function branchesServiced()
	{
		return $this->belongsToMany(Branch::class);
	}


	public function manages() {
		
		return $this->hasMany(Branch::class);

	}
	public function comments () {
		
		return $this->hasMany(Comment::class);

	}
	public function managesAccount () {
		
		return $this->hasMany(Company::class);

	}
	 public function userdetails()
	 {
		  return $this->belongsTo(User::class,'user_id','id');
	 }
	
	public function authored () {
		
		return $this->hasMany(News::class);

	}
	
	public function fullName()
	{
		return $this->attributes['lastname'] . ',' . $this->attributes['firstname'];
	}
	
	public function postName()
	{
		return $this->attributes['firstname'] . ' ' . $this->attributes['lastname'];
	}
	
	public function industryfocus()
	{
		return $this->belongsToMany(SearchFilter::class)->withTimestamps(); 
	}


	public function findPersonsRole($people)
	{
		
		foreach ($people->userdetails->roles as $role)
		{
			$result[] = $role->name;
		}
		
		return $result;
		
	}

	
	private function getPersonsServiceLines(){

		foreach($this->person->serviceline as $serviceline){

			$servicelines[]=$serviceline->id;
		}
		$this->personServicelines = implode("','",$servicelines);
		
	}
}