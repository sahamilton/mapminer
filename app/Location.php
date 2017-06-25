<?php
namespace App;

class Location extends Model {

	// Add your validation rules here
	public static $rules = [
		'businessname' => 'required',
		'street' => 'required',
		'city' => 'required',
		'state' => 'required',
		'zip' => 'required',
		'company_id' => 'required',
		'segment' => 'required',
		'businesstype' => 'required'
		
	];

	// Don't forget to fill this array
	// Note this array is used to check the imports as well.  
	// If you change this you will have to change the location import template.
	

	public $fillable = ['businessname','street','suite','city','state','zip','company_id','phone','contact','lat','lng','segment','businesstype'];
	
	protected $hidden =  array('created_at','updated_at','id');
	
	public function relatedNotes() {
		return $this->hasMany(Note::class)->with('writtenBy');
	}
	
	public function company() {
		
		return $this->belongsTo(Company::class)->with('managedBy');

	}

	public function branch () {
		
		return $this->belongsTo(Branch::class);

	}
	
	public function instate () {
		
		return $this->belongsTo(State::class,'state','statecode');

	}
	
	public function verticalsegment () {
		return $this->hasOne(SearchFilter::class,'id','segment');
	}
	
	public function clienttype() {
		
		return $this->hasOne(SearchFilter::class,'id','businesstype');
	}
	
	
	/*
		Find company locations from db as a function of lat, lng, distance and limit 
		
		Definitions:                                                           
			South latitudes are negative, east longitudes are positive           
		
		Passed to function:                                                   
			lat, lon = Latitude and Longitude of reference position  
    		distance in miles (note could add kilometers as another function)
			number is the limit

		Added filter on users service lines
				
		@return result
	
	*/	
	
	public function findNearbyLocations($lat,$lng,$distance,$number,$company=NULL,$userServiceLines, $limit=null, $verticals=null)
	
	{
		

		if(null=== $userServiceLines){
					$userServiceLines = $this->getUserServiceLines();
		}
		if(! $verticals){
			$keyset = ['vertical','segment','businesstype'];
			$searchKeys = array();
			$filtered = $this->isFiltered(['companies','locations'],['vertical','segment','business']);
			
			if($filtered)
			{
				
				$searchKeys = $this->getQuerySearchKeys();
				
				
			}
		}else{
			$keyset = ['vertical','segment','businesstype'];
			$searchKeys['vertical']['keys'] = implode("','",$verticals);
		}
		
		$params = array(":loclat"=>$lat,":loclng"=>$lng,":distance"=>$distance);
		
		// Get the users serviceline associations
		// 
		
		
			
		$query = "SELECT id,businessname,phone,contact,companyname,company_id,street,city,state,zip,lat,lng, distance_in_mi,segment,businesstype,vertical
			  FROM (
					SELECT DISTINCT locations.id as id, 
						businessname,
						phone,
						contact,
						searchfilters.filter as vertical,
						locations.segment as segment, 
						locations.businesstype as businesstype,
						companyname,
						locations.company_id,
						street,	city,state,zip,
						lat,lng,r,
							   69.0 * DEGREES(ACOS(COS(RADIANS(latpoint))
										 * COS(RADIANS(lat))
										 * COS(RADIANS(longpoint) - RADIANS(lng))
										 + SIN(RADIANS(latpoint))
										 * SIN(RADIANS(lat)))) AS distance_in_mi
					 FROM 
					 	locations,companies,searchfilters,company_serviceline
					 JOIN (
							SELECT  ".$lat."  AS latpoint,  ".$lng." AS longpoint, ".$distance." AS r
					   ) AS p
					 WHERE 
					 	lat
					  		BETWEEN latpoint  - (r / 69)
						  	AND latpoint  + (r / 69)
					  	 AND lng
					  		BETWEEN longpoint - (r / (69 * COS(RADIANS(latpoint))))
						  	AND longpoint + (r / (69 * COS(RADIANS(latpoint))))
						AND companies.id = company_serviceline.company_id
						AND company_serviceline.serviceline_id in ('".implode("','",$userServiceLines)."')
						AND locations.company_id = companies.id
						AND companies.vertical = searchfilters.id ";
						
					if ($company!=NULL){
						$query.=" and companies.id = '".$company."' ";
					}
					foreach ($keyset as $key)
					{
						
						if(isset($searchKeys[$key]['keys'])){
							$query .= " AND (".$key." in ('".$searchKeys[$key]['keys']."')";
							
							if(isset($searchKeys[$key]['null'] ) && $searchKeys[$key]['null']=='Yes') {
								$query.=" OR ". $key . " IS NULL";
							}
						$query.=")";
						}
					}

					 $query.=" ) d
			 
			 WHERE distance_in_mi <= r ";
			 
			 $query.="  ORDER BY distance_in_mi";

			if(isset($limit))
			 {
			 		$query.=" limit " . $limit;
			 }
		
			 $result = \DB::select($query);

		return $result;
	}
	
	public function locationAddress()
	{
		return ($this->street . " " . $this->address2 . " " .$this->city . " " . $this->state);
	}
	/*
		Generate Mapping xml file from location results
		       
		Passed to function:                                                   
		@ results                             

            
		
		@return xml
	
	*/	
	
	public function makeNearbyLocationsXML($result) {
		$content = view('locations.xml', compact('result'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
		/*$dom = new \DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		foreach($result as $row){
			
		  // ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("locationweb",route('location.show' , $row->id) );
			$newnode->setAttribute("name",trim($row->businessname));
			$newnode->setAttribute("account",trim($row->companyname));
			$newnode->setAttribute("accountweb",route('company.show' , $row->company_id,array('title'=>'see all locations') ));
			$newnode->setAttribute("address", $row->street." ". $row->city." ". $row->state);
			$newnode->setAttribute("lat", $row->lat);
			$newnode->setAttribute("lng", $row->lng);
			$newnode->setAttribute("id", $row->id);
			$newnode->setAttribute("vertical", $row->vertical);	
		}
		return trim($dom->saveXML());*/

		/*
		if(count($participant['watched'])==1) {
				$newnode->setAttribute("watch",true);
				$newnode->setAttribute("category",'Watched');
			}else{
				$newnode->setAttribute("watch",false);
			}

		 */
	}
	
	public function makeNearbyLocationsXMLObject($result) {
		

		if (App::environment() == 'local'){
			/*\Debugbar::disable();*/
		}
		$dom = new \DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		foreach($result as $row){

			
		  // ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("locationweb",route('location.show' , $row->id) );
			$newnode->setAttribute("name",trim($row->businessname));
			$newnode->setAttribute("account",trim($row->companyname));
			$newnode->setAttribute("accountweb",route('company.show' , $row->company_id,array('title'=>'see all locations') ));
			$newnode->setAttribute("address", $row->street." ". $row->city." ". $row->state);
			$newnode->setAttribute("lat", $row->lat);
			$newnode->setAttribute("lng", $row->lng);
			$newnode->setAttribute("id", $row->id);	
			
		}
		return $dom->saveXML();
	}


	/*
		 * Calculate the distance between two lat/ lng pairs
			Definitions:                                                           
			South latitudes are negative, east longitudes are positive           
		
		 * @param  integer $lat1 Origin Latitude (in decimal degrees)  
		 * @param  integer $lon1 Origin Longitude (in decimal degrees)  
		 * @param  integer $lat2 Destination Latitude (in decimal degrees)  
		 * @param  integer $lon2 Destination Longitude (in decimal degrees)  
		 * @param  alpha   $unit the unit you desire for results 
		 *                        where: 'blank' is statute miles                                  
					                  'K' is kilometers (default)                          
					                  'N' is nautical miles   
		 * @return integer       Distance
		 *
	*/	
	public function distanceBetween($lat1, $lon1, $lat2, $lon2, $unit) {
		
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	
	
	private function getQuerySearchKeys()
	{
			$keys = array();
			$searchKeys = array();
					
			$keys['vertical'] = $this->getSearchKeys(['companies'],['vertical']);
		
			if(count($keys['vertical']) > 0){
				
				$searchKeys['vertical']['keys'] = implode("','",$keys['vertical']);
				$searchKeys['vertical']['null']= $this->isNullable($keys['vertical']);
			}
			
			
			$keys['segment'] = $this->getSearchKeys(['locations'],['segment','businesstype']);
			if(count($keys['segment']) > 0){
				
				$searchKeys['segment']['keys'] = implode("','",$keys['segment']);
				$searchKeys['segment']['null'] = $this->isNullable($keys['segment']);
			}
			
			
			$keys['businesstype'] = $this->getSearchKeys(['locations'],['businesstype']);
			
			if(count($keys['businesstype']) > 0){
				
				$searchKeys['businesstype']['keys'] = implode("','",$keys['businesstype']);
				$searchKeys['businesstype']['null'] = $this->isNullable($keys['businesstype']);
				
			}
			
			return $searchKeys;	
	}
	
}