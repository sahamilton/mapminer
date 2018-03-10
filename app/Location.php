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


	public $table = 'locations';
	public $branch;
	// Don't forget to fill this array
	// Note this array is used to check the imports as well.  
	// If you change this you will have to change the location import template.
	

	public $fillable = ['businessname','street','suite','city','state','zip','company_id','phone','contact','lat','lng','segment','businesstype'];
	
	protected $hidden =  array('created_at','updated_at','id');
	
	public function relatedNotes() {

		return $this->hasMany(Note::class,'related_id')->where('type','=','location')->with('writtenBy');

	}
	
	public function company() {
		
		return $this->belongsTo(Company::class)->with('managedBy');

	}

	public function branch () {
		
		return $this->belongsTo(Branch::class);

	}
	public function contacts(){
		return $this->hasMany(Contacts::class);
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
	


	public function watchedBy(){

		return $this->belongsToMany(User::class,'location_user','location_id','user_id')->withPivot('created_at','updated_at');
	}

	public function nearbyBranches(){

		return Branch::nearby($this,'100')->limit(5);
	}

	
	public function nearbySalesRep($serviceline=null){

		return Person::nearby($this,'100')
		->with('userdetails.roles')
		->whereHas('userdetails.serviceline',function ($q) use ($serviceline){
			$q->whereIn('servicelines.id',$serviceline);
		})
		->whereHas('userdetails.roles',function ($q){
			$q->where('roles.name','=','Sales');
		})
            
        ->limit(5);

		//return Branch::nearby($this,'100')->limit(5);
	}
	


	public function locationAddress(){
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
	public function getStateSummary($id){
		return $this->where('company_id','=',$id)->select('state', \DB::raw('count(*) as total'))
             ->groupBy('state')
             ->pluck('total','state');
	}
	
	private function getQuerySearchKeys(){
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
			;
		
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