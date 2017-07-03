<?php
namespace App;

class Location extends Model {
	
	use Geocode;
	
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
		
	}

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
	public function importQuery($data){
		$data['temptable'] = $data['table'] .'_import';	
		$this->executeQuery("CREATE TEMPORARY TABLE ".$data['temptable']." AS SELECT * FROM ". $data['table']." LIMIT 0");
				
		
		$data['import'] = $this->_import_csv($data['basepath'],$data['temptable'],$data['fields']);
		// make sure we bring the created at field across
		$data['fields'].=",created_at";
		
		$this->executeQuery("update ".$data['temptable']." set company_id ='".$data['company_id']."', created_at ='".date("Y-m-d H:m:s")."'");
		
		
		$this->executeQuery("update ".$data['temptable']." set company_id ='".$data['company_id']."'");
		
		
		if (isset($data['segment'])){
			$this->executeQuery("update ".$data['temptable']." set segment ='".$data['segment']."'");
		}
		
		
		
		$this->executeQuery("INSERT INTO `".$data['table']."` (".$data['fields'].") SELECT ".$data['fields']." FROM `".$data['temptable']."`");
		// seems that when copying temp table null values get changed to 0
		$this->executeQuery("UPDATE `".$data['table']."` set segment = NULL where segment = 0");
		$this->executeQuery("UPDATE `".$data['table']."` set businesstype = NULL where businesstype = 0");
		
		$this->executeQuery("DROP TABLE ".$data['temptable']);
	}

	private function executeQuery($query)
	{
		
		$results = \DB::statement($query);
	}
}