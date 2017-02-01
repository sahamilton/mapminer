<?php
namespace App;
class Branch extends Model {

	// Add your validation rules here
	public static $rules = [
		'branchname'=>'required',
		'branchnumber'=>'required',
		'street' => 'required',
		'city' => 'required',
		'state'=>'required',
		'zip'=>'required',
		'person_id'=>'required',
		'region_id'=>'required'
	];

	// Don't forget to fill this array
	public $fillable = [
		'lat',
		'lng',
		'branchname',
		'branchnumber',
		'street',
		'address2',
		'city',
		'state',
		'zip',
		'phone',
		'person_id',
		'region_id',
		'radius'];
	
	public $errors;
	
	public function locations() 
	{
		return $this->hasMany(Location::class);
		
	}
	
	public function region() 
	{
		return $this->belongsTo(Region::class);
		
	}
	
	public function instate() 
	{
		return $this->belongsTo(State::class,'state','statecode');
		
	}
	
	public function manager() 
	{
		return $this->belongsTo(Person::class,'person_id');
		
	}
	
	public function servicelines()
	{
			return $this->belongsToMany(Serviceline::class);
	}
	
	public function servicedBy()
	{
		return $this->belongsToMany(Person::class);
	}
	
	
	
	public function findNearbyBranches($lat,$lng,$distance,$number,$userServiceLines)
	{
		
		if (! $userServiceLines)
		{
			$userServiceLines = $this->userServiceLines;
		}
		$coordinates = $this->getPositionCoordinates($lat,$lng,$distance, $number);
	
		$query = "select haversine(x(position), y(position), ".$coordinates['lat'].",".$coordinates['lon'].") as distance_in_mi, 
		branches.id as branchid,branchnumber,branchname,street,address2,city,state,zip,lat,lng,Serviceline as servicelines,color
		from branches,branch_serviceline,servicelines
		where st_within(position, 
			envelope(
						linestring(
							point(".$coordinates['rlat1'].",".$coordinates['rlon1']."), 
							point(".$coordinates['rlat2'].",".$coordinates['rlon2'].")
						)
					)
			)
			and branches.id = branch_serviceline.branch_id
		    			and branch_serviceline.serviceline_id = servicelines.id
		    			AND branch_serviceline.serviceline_id in ('".implode("','",$userServiceLines)."')
		order by distance_in_mi
		limit " . $number;


		$result = \DB::select($query);	 

		return $result;
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
	/*
		Find branches from db as a function of lat, lng, distance and limit 
		
		Definitions:                                                           
			South latitudes are negative, east longitudes are positive           
		
		Passed to function:                                                   
			lat, lon = Latitude and Longitude of reference position  
    		distance in miles (note could add kilometers as another function)
			number is the limit
				
		@return result
	
	*/	
	
		
	/*
		Generate Mapping xml file from branches results
		       
		Passed to function:                                                   
		@ results                             
	
		@return xml
	
	*/	
	
	
	public function makeNearbyBranchXML($result) {
		
		$dom = new DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		foreach($result as $row){
			
		  // ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("name",trim($row['branchname']));
			$newnode->setAttribute("address", 
				trim($row['street'])." ". 
				trim($row['city'])." ". 
				trim($row['state']));
			$newnode->setAttribute("lat", $row['lat']);
			$newnode->setAttribute("lng", $row['lng']);
			if(isset($row['id']))
			{
				$newnode->setAttribute("locationweb",route('branch.show',$row['id']) );
				$newnode->setAttribute("id", $row['id']);	
			}else{
				$newnode->setAttribute("locationweb",route('branch.show',$row['branchid']) );
				$newnode->setAttribute("id", $row['branchid']);	
			}
			$newnode->setAttribute("type", 'branch');
			if(isset($row['serviced_by']) )
			{
				$newnode->setAttribute("salesreps", count($row['serviced_by']));
			}
			
				if(is_object($row['servicelines']) && count($row['servicelines']) > 0){
					
					$newnode->setAttribute("brand", $row['servicelines'][0]['ServiceLine']);
					$newnode->setAttribute("color", $row['servicelines'][0]['color']);
				}
				if( is_string($row['servicelines'])){
					
					$newnode->setAttribute("brand", $row['servicelines']);
					$newnode->setAttribute("color", $row['color']);
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