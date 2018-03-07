<?php
namespace App;

class Branch extends Model {
	use Geocode;
	public $table ='branches';

	protected $primaryKey = 'id'; // or null

    public $incrementing = false;


	public $branchRoles = [5,9,11];
	public $branchmanagerrole = 9;
	// Add your validation rules here
	public static $rules = [
		'branchname'=>'required',
		'id'=>'required',
		'street' => 'required',
		'city' => 'required',
		'state'=>'required',
		'zip'=>'required',

	];
	public $branchManagerRole = 9;
	// Don't forget to fill this array
	public $fillable = [
		'id',
		'lat',
		'lng',
		'branchname',
		'street',
		'address2',
		'city',
		'state',
		'zip',
		'phone',
		'region_id'];
	protected $guarded = [];
	public $errors;
	
	public function locations() 
	{
		return $this->hasMany(Location::class);
		
	}
	
	public function region() 
	{
		return $this->belongsTo(Region::class);
		
	}
	
	public function relatedPeople($role=null){
		if($role){

			return $this->belongsToMany(Person::class)
			->wherePivot('role_id','=',$role);
		}else{
			return $this->belongsToMany(Person::class)->withPivot('role_id');
		}
		
	}

	public function instate() 
	{
		return $this->belongsTo(State::class,'state','statecode');
		
	}
	
	public function manager() 
	{
		return $this->relatedPeople($this->branchManagerRole)->wherePivot('role_id','=',$this->branchmanagerrole);;
		
	}
	
	public function servicelines()
	{
			return $this->belongsToMany(Serviceline::class);
	}
	
	public function servicedBy()
	{
		return $this->belongsToMany(Person::class);
	}
	
	
	/* 
		Calculate bounding box coordinates

	*/
	private function getPositionCoordinates($lat,$lng,$distance)
	{
		

		$coordinates['lat']= $lat;
		$coordinates['lon'] = $lng;
		$coordinates['dist'] = $distance;
		$location = Geolocation::fromDegrees($lat,$lng);
		$box = $location->boundingCoordinates($distance,'mi');

		$coordinates['rlon1'] = $box['min']->degLon;
		$coordinates['rlon2'] = $box['max']->degLon;
		$coordinates['rlat1'] = $box['min']->degLat;
		$coordinates['rlat2'] = $box['max']->degLat;
	
		return $coordinates;
	}
	
	public function getBranchIdFromid($branchstring){
		$branchstring = str_replace(" ","", $branchstring);
		return $this->whereIn('id',explode(',',$branchstring))
		->pluck('id')->toArray();
	}
		
	/*
		Generate Mapping xml file from branches results
		       
		Passed to function:                                                   
		@ results                             
	
		@return xml
	
	*/	
	
	
	public function makeNearbyBranchXML($result) {
		
		$dom = new \DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		foreach($result as $row){
			
		  // ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("name",trim($row->branchname));
			$newnode->setAttribute("address", 
				trim($row->street)." ". 
				trim($row->city)." ". 
				trim($row->state));
			$newnode->setAttribute("lat", $row->lat);
			$newnode->setAttribute("lng", $row->lng);
			if(isset($row->id))
			{
				$newnode->setAttribute("locationweb",route('branches.show',$row->id) );
				$newnode->setAttribute("id", $row->id);	
			}else{
				$newnode->setAttribute("locationweb",route('branches.show',$row->branchid) );
				$newnode->setAttribute("id", $row->branchid);	
			}
			$newnode->setAttribute("type", 'branch');
			if(isset($row->serviced_by) )
			{
				$newnode->setAttribute("salesreps", count($row->serviced_by));
			}
			
				if(is_object($row->servicelines) && count($row->servicelines) > 0){
					
					$newnode->setAttribute("brand", $row->servicelines[0]->ServiceLine);
					$newnode->setAttribute("color", $row->servicelines[0]->color);
				}
				if( is_string($row->servicelines)){
					
					$newnode->setAttribute("brand", $row->servicelines);
					//$newnode->setAttribute("color", $row->color);
				}
				
				
		}	

		return $dom->saveXML();

		

	}
	
	
	public function getBranchManagers()
	{
		// this shouldnt be hardcoded!
		$roles=['3'];
		$accountmanagers = User::whereHas('roles', 
			function($q) use($roles){
			$q->whereIn('role_id',$roles);
			})->with('Person')->get();
		foreach ($accountmanagers as $manager) 
		{
			$managers[$manager->person->id] = $manager->person->firstname . " ". $manager->person->lastname;
		
		}
		return $managers;
	}

	
}