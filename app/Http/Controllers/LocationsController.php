<?php
namespace App\Http\Controllers;
use App\Watch;
use App\Branch;
use App\Company;
use App\Location;


class LocationsController extends BaseController {
	public $distance = '400';
	/**
	 * Display a listing of locations
	 *
	 * @return Response
	 */
	public $location;
	public $watch;
	public $limit = 10;
	public $waitSeconds =5;
	public $companyServicelines;
	public $serviceline;
	public $userServiceLines;

	protected $branch;
	public function __construct(Location $location, Branch $branch, Company $company, Watch $watch){
		$this->location = $location;
		$this->watch = $watch;
		$this->company = $company;
		$this->branch = $branch;
		parent::__construct();
	}
	
	
	public function index()
	{
		$this->userServiceLines = $this->location->getUserServiceLines();
		$companies = $this->company->orderBy('companyname')->pluck('companyname','id');
		
		return \View::make('locations.index',compact('companies'));
	}

	/**
	 * Show the form for creating a new location
	 *
	 * @return Response
	 */
	public function create($accountID)
	{
		$segments = \DB::table('searchfilters')
				->select ('searchfilters.id as id','filter')
				->join('locations','locations.segment','=','searchfilters.id')
				->where('locations.company_id','=',$accountID)
				->distinct()
				->pluck('filter','id');
		$segments['Not Specified']=NULL;

		$location = $this->company->findOrFail($accountID);
		
		return \View::make('locations.create',compact('location', 'segments'));
	}

	/**
	 * Store a newly created location in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		if(! $this->location->isValid($input = \Input::all())){
			var_dump(\Input::all());
			dd($this->location->errors);
			return \Redirect::back()->withInput()->withErrors($this->location->errors);
		}
		
		
		$this->location = $this->location->create($input);
				
		// add lat lng to location
		
		$address = $input['street'] . ",". $input['city'] .",". $input['state']." ". $input['zip'];
		$this->geoCodeAddress($address);
		
		return \Redirect::route('location.show',$this->location->id);
	}

	/**
	 * Display the specified location.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function state($id,$state)
	{
		$location = $this->location->where('state','=',$state)->where('company_id','=',$id)->get();
		$filtered = $this->location->isFiltered(['locations'],['segment','business']);	
		return \View::make('locations.state', compact('location','filtered'));
	}
	
	public function getStateLocations ($id,$state) {
		
		$location = $this->location->with('company')
			->where('state','=',$state)
			->where('company_id','=',$id)
			->get();

		echo $this->location->makeNearbyLocationsXML($location);
	}
	
	public function show($id)
	{
	
		$this->location = $this->location
			->with('company','company.industryVertical','company.serviceline','relatedNotes','clienttype','verticalsegment')
			->findOrFail($id->id);
		

		$this->getCompanyServiceLines();
		$branch = $this->findBranch(1);
		$watch = $this->watch->where("location_id","=",$id->id)->where('user_id',"=",\Auth::id())->first();
		$location = $this->location;
		return \View::make('locations.show', compact('location','branch','watch'));
	}
	
	private function getCompanyServiceLines(){

		foreach($this->location->company->serviceline as $serviceline){

			$servicelines[]=$serviceline->id;
		}
		$this->companyServicelines = implode("','",$servicelines);
		
	}





	public function summaryLocations($id)
	{

		$locations =\DB::table('locations')
                 ->select('state', \DB::raw('count(*) as total'))
				 ->where('company_id','=',$id)
                 ->groupBy('state')
                 ->get();
		
		foreach ($locations as $state) {
			echo $state['state'] . "     ". $state['total']."<br />";
			
		}
	}
	public function map($id)
	{
		if (App::environment() == 'local'){
			\Debugbar::disable();
		}
		$location = $this->location->with('company')->findOrFail($id);

		$dom = new \DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		  // ADD TO XML DOCUMENT NODE
		 
		  $node = $dom->createElement("marker");
		  $newnode = $parnode->appendChild($node);
		  $newnode->setAttribute("name",$location['branchname']);
		  $newnode->setAttribute("address", $location['street']. " ". $location['city'] ." ". $location['state']." ". $location['zip']);
		  $newnode->setAttribute("lat", $location['lat']);
		  $newnode->setAttribute("lng", $location['lng']);
		  $newnode->setAttribute("type", $location['type']);
		
		
		echo $dom->saveXML();
	}
	/**
	 * Show the form for editing the specified location.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($location)
	{
		
		return \View::make('locations.edit', compact('location'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($location)
	{
		$this->location = $location;
		
		
		$input = \Input::only('businessname','street','city','state','zip','company_id','id','phone','contact','segment','businesstype');
		
		if(! $this->location->isValid($input)){
			return \Redirect::back()->withInput()->withErrors($this->location->errors);
		}
// geocode location

		$this->location->update($input);
		$address = $input['street'] . ",". $input['city'] .",". $input['state']." ". $input['zip'];
		$this->geoCodeAddress($address);
		$company_id = $input['company_id'];
		
		return \Redirect::route('location.show',$this->location->id );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($location)
	{

		$this->location = $this->location->findOrFail($location);
		$companyid = $this->location->company_id;
		$this->location->destroy($location);

		return \Redirect::route('company.show',$companyid);
	}

	
	
	
	
	
	/**
	 * Used to find the closest branches to any location bsaed on servicelines
	 * @param  integer $limit number of branches to return
	 * @return object         [description]
	 */
	private function findBranch($limit = 5) {
			
		if(! is_array($this->companyServicelines))
		{
			$userservicelines = explode("','",$this->companyServicelines);
		}else{

			$userservicelines = $this->companyServicelines;
		}
		
		$branch = $this->branch;
		$branches = $branch->findNearbyBranches($this->location->lat,$this->location->lng,50,$limit,$userservicelines);
		


		return $branches;
		
	}
	
	
	
	/**
	 * Return view of closest n branches
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getClosestBranch($id,$n=5)
	
	{
		if (\Input::get('d')) {
			$this->distance = \Input::get('d');
		}
		$this->location = $this->location->with('company','company.serviceline')->findOrFail($id);

		$this->getCompanyServiceLines();
		$data['location']= $this->location;
		$data['branch'] = $this->findBranch($n);
		

		return \View::make('branches.assign', compact('data'));

		
	}

	public function showLocationsNearbyBranches($id)
	{	
		if (\Input::get('d')) {
			$this->distance = \Input::get('d');
		}
		$this->location = $this->location->with('company','company.serviceline')->findOrFail($id);
		$this->getCompanyServiceLines();
		$data['location']= $this->location;
		$branches= $this->findBranch(5);
		$branch = $this->branch;
		echo $branch->makeNearbyBranchXML($branches);

	}
	public function getClosestBranchMap($id,$n=5)
	{
		$this->location = $this->location->with('company','company.serviceline')->findOrFail($id);
		$data['location']= $this->location;
		$servicelines = $this->serviceline->all();
		return \View::make('branches.nearbymap', compact('data','servicelines'));

	}
	public function showNearbyLocations()
	{
		
		if (\Input::get('d')) {
			$data['distance'] = \Input::get('d');
		}else{
			$data['distance'] = '50';
		}
		
		//$data['branches'] = $this->branch->findOrFail($id);

		

		return \View::make('locations.nearby', compact('data'));
	}



	public function mapNearbyLocations(){
		$result = $this->getNearbyLocations();
		echo $this->location->makeNearbyLocationsXML($result);
	}



	
	public function listNearbyLocations($id){
		
		$filtered = $this->location->isFiltered(['companies'],['vertical']);
		
		$mywatchlist= array();
		$locations = NULL;
		$branches = $this->branch->with('manager')->findOrFail($id);
		
		$data['manager'] = !isset($data['manager']) ? array() : Person::find($data['branch']->person_id);

		$data['branch'] = $branches;
		$data['title']='National Accounts';
		$locations  = $this->getNearbyLocations($branches->lat,$branches->lng);
		$watchlist = User::where('id','=',\Auth::id())->with('watching')->get();
		foreach($watchlist as $watching) {
			foreach($watching->watching as $watched) {
				$mywatchlist[]=$watched->id;
			}
		}

		$fields = array('Business Name'=>'businessname','Street'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Watching'=>'watch');
		return \View::make('branches.showlist', compact('data','locations','mywatchlist','fields','filtered'));
	}
		
	
	private function getNearbyLocations($lat=NULL,$lng=NULL,$distance=NULL,$company_id = null)
	
	{
		
		
		if (\Input::get('d')) {
			$distance = \Input::get('d');
		}else{
			$distance = '10';
		}
		
		if(\Input::get('lat')){
			$loclat = \Input::get('lat');
			$loclng = \Input::get('lng');
			
		}elseif (isset($lat) && isset($lng)){
			$loclat = $lat;
			$loclng = $lng;
		}else{
			$loclat = '47.25';
			$loclng = '-122.44';
		}
		
		$result = $this->location->findNearbyLocations($loclat,$loclng,$distance,$number=1,$company_id,$this->userServiceLines);
		return $result;		
		

		
	}
	
	
	/**
	 * [locationnotes description]
	 * @return [type] [description]
	 */
	public function locationnotes()
	{
		$query = "select 
			companyname, 
			companies.id as companyid, 
			locations.id as locationid, 
			businessname, note, 
			concat(firstname,' ',lastname) as posted_by, 
			notes.created_at as dateposted 
		from companies, locations,notes, users, persons 
		where notes.location_id = locations.id 
		and locations.company_id = companies.id 
		and notes.user_id = users.id 
		and persons.user_id = users.id 
		
		
		order by companyname,notes.created_at";
		$notes = \DB::select(\DB::raw($query));
		$fields=['Company'=>'companyname','Location Name'=>'businessname','Note'=>'note','Posted By'=>'posted_by','Date'=>'dateposted'];
		return \View::make('locations.notes',compact('notes','fields'));
	}	
	
	
	public function bulkImport() {
		
		// Check that we have a file
		
		$rules= ['upload' => 'required', 'company'=>'required','segment'=>'required'];

		

		$validator = Validator::make(\Input::all(), $rules);

    	if ($validator->fails())
		{
			
			return \Redirect::back()->withErrors($validator);
		} 
		
		$segment = \Input::get('segment');
		if($segment == 0)
		{
			$segment = NULL;
		}	
		
		$company_id = \Input::get('company');

		
		// Make sure its a CSV file
		$file = $this->location->checkImportFileType($rules);
		
		if(!is_object($file)) {
		
			return \Redirect::back()->withErrors(['Invalid file format.  It needs to be a csv. file']);
			
		}
		
		// Rename and Move file to correct server location  
		$name = $file->getClientOriginalName();
		$newname = time() . '-' . $name;
		$path = Config::get('app.mysql_data_loc');
		
		
		// Moves file to  mysql data folder on server
		$file->move($path, $newname);
		
		
		// Make sure that the file data is correct
		$filename =  $path.$newname; 

		$data = $this->location->checkImportFileStructure($filename);
		
		$fields = implode(",",$data);


		if($data !== $this->location->fillable){

			return \Redirect::back()->withErrors(['Invalid file format.  Check the fields:<br />', array_diff($this->location->fillable,$data)]);
		}
		$table ='locations';
		
		$temptable = $table .'_import';		
		
		$this->executeQuery("CREATE TEMPORARY TABLE ".$temptable." AS SELECT * FROM ". $table." LIMIT 0");
				
		
		$data = $this->location->_import_csv($filename,$temptable,$fields);
		// make sure we bring the created at field across
		$fields.=",created_at";
		$now = date("Y-m-d H:m:s");
		$this->executeQuery("update ".$temptable." set company_id ='".$company_id."', created_at ='".$now."'");
		
		
		$this->executeQuery("update ".$temptable." set company_id ='".$company_id."'");
		
		
		if ($segment){
			$this->executeQuery("update ".$temptable." set segment ='".$segment."'");
		}
		
		
		
		$this->executeQuery("INSERT INTO `locations` (".$fields.") SELECT ".$fields." FROM `".$temptable."`");
		// seems that when copying temp table null values get changed to 0
		$this->executeQuery("UPDATE `locations` set segment = NULL where segment = 0");
		$this->executeQuery("UPDATE `locations` set businesstype = NULL where businesstype = 0");
		
		$this->executeQuery("DROP TABLE ".$temptable);
		

		return \Redirect::to('/company/'.$company_id);
	}
	
	private function executeQuery($query)
	{
		
		$results = \DB::statement($query);
	}
	
	public function bulkGeoCodeLocations()
	{
		$locations = Location::where(['lat'=>NULL,'geostatus'=>TRUE])->orWhere(['lat'=>'0','geostatus'=>TRUE])->get();
		
		$n =0;
		foreach ($locations as $location) {
			$this->location = Location::find($location->id);
			if($n > $this->limit)
			{
				sleep($this->waitSeconds);	
				$n = 0;
			}
			$n++;
			$address = $location->street . ",". $location->city .",". $location->state." ". $location->zip;
			$this->geoCodeAddress($address);
			
		}
	
		echo "All done!";
	}
	
	
	private function geoCodeAddress($address)
	{
		

		$geoCode = $this->getLatLng($address);
			
			if(! $geoCode)
			{
				$this->badAddress($geoCode);
				
			}else{
				$this->updateLatLng($geoCode);
				$this->updateLatLng($geoCode);
				
				
			}
			
	}
	
	private function getLatLng($address)
	{
		try {
			
		$geocode = Geocoder::geocode($address);
		// The GoogleMapsProvider will return a result
		return $geocode;
		
		} catch (\Exception $e) {
			// No exception will be thrown here
			//echo $e->getMessage();
		}
		
	}
	
	private function updateLatLng($geoCode)
	{
		
		
		$this->location->lat = $geoCode['latitude'];
		$this->location->lng = $geoCode['longitude'];
		$this->location->geostatus = TRUE;
		$this->location->save();
		
	}
	
	private function badAddress($address)
	{
		
		$this->location->geostatus = FALSE;
		$this->location->save();
		
	}
}