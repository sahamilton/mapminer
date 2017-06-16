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

	public function salesleads(){
		return $this->belongsToMany(Lead::class, 'lead_person_status')
		->withTimestamps()
		->withPivot('status_id','rating');
	}
	
	public function findPersonsRole($people)
	{
		
		foreach ($people->userdetails->roles as $role)
		{
			$result[] = $role->name;
		}
		
		return $result;
		
	}
	public function salesLeadsByStatus($id){
		$leads = $this->with('salesleads')
			->whereHas('salesleads',function($q){
				$q->where('datefrom','<=',date('Y-m-d'))
				->where('dateto','>=',date('Y-m-d'));
			})
			->find($id);
		
		foreach ($leads->salesleads as $lead){
			if(! isset($statuses[$lead->pivot->status_id])){
				$statuses[$lead->pivot->status_id]['status']=$lead->pivot->status_id;
				$statuses[$lead->pivot->status_id]['count']=0;
			}
			$statuses[$lead->pivot->status_id]['count']+=1;
			
		}
		return $statuses;
	}
	
	private function getPersonsServiceLines(){

		foreach($this->person->serviceline as $serviceline){

			$servicelines[]=$serviceline->id;
		}
		$this->personServicelines = implode("','",$servicelines);
		
	}

	
	
	public function findNearByPeople($lat,$lng,$distance,$limit=null, $role=null,$verticals=null){
		$query = "SELECT id,firstname,lastname,lat,lng, email,distance_in_mi,employee_id,role,city,state
			  FROM (
					SELECT DISTINCT persons.id as id, 
						firstname,lastname,lat,lng,city,state,users.email as email, users.employee_id as employee_id, roles.name as role,r,
							   69.0 * DEGREES(ACOS(COS(RADIANS(latpoint))
										 * COS(RADIANS(lat))
										 * COS(RADIANS(longpoint) - RADIANS(lng))
										 + SIN(RADIANS(latpoint))
										 * SIN(RADIANS(lat)))) AS distance_in_mi
					 FROM 
					 	persons,users,role_user,roles";
					 if($verticals){
					 	$query.=", person_search_filter ";
					 }

					 $query .= "JOIN (
							SELECT  ".$lat."  AS latpoint,  ".$lng." AS longpoint, ".$distance." AS r
					   ) AS p
					 WHERE 
					 	lat
					  		BETWEEN latpoint  - (r / 69)
						  	AND latpoint  + (r / 69)
					  	 AND lng
					  		BETWEEN longpoint - (r / (69 * COS(RADIANS(latpoint))))
						  	AND longpoint + (r / (69 * COS(RADIANS(latpoint))))
						 AND users.id = persons.user_id
						 AND users.id=role_user.user_id
						 AND role_user.role_id = roles.id";
						 if(isset($role)){
						 	$query.=" and roles.name ='". $role."'";
						 }
						 if($verticals){
						 	$query.=" and persons.id = person_search_filter.person_id
						 			and person_search_filter.search_filter_id in ('" .
						 			implode("','",$verticals) ."')";
						 }
						$query.=" ) d
			 
			 WHERE distance_in_mi <= r 
			 ORDER BY distance_in_mi";

			if(isset($limit))
			 {
			 		$query.=" limit " . $limit;
			 }
			$query = str_replace("\r\n",' ',$query);
			$query = str_replace("\t",'',$query);

			$result = \DB::select($query);

		return $result;
	}
	
	public function ownedLeads(){
		return $this->belongsToMany(Lead::class, 'lead_person_status')
		->withTimestamps()
		->where('datefrom','<=',date('Y-m-d'))
		->where('dateto','>=',date('Y-m-d'))
		->withPivot('status_id','rating')
		->whereIn('status_id',[2]);
	}


	public function myOwnedLeads(){
		return $this->belongsToMany(Lead::class, 'lead_person_status')
		->withTimestamps()
		->withPivot('status_id','rating')
		->whereIn('status_id',[2])
		->where('person_id','=',auth()->user()->person->id);
	}

	public function offeredLeads(){

		return $this->belongsToMany(Lead::class, 'lead_person_status')
		->withTimestamps()
		->withPivot('status_id','rating')
		->whereIn('status_id',[1]);
        
        
       
    }

    

    
}