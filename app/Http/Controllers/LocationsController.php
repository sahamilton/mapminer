<?php
namespace App\Http\Controllers;
use App\Watch;
use App\Branch;
use App\Company;
use App\User;
use App\Location;
use App\Serviceline;
use Illuminate\Http\Request;
use App\Http\Requests\LocationFormRequest;


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
		parent::__construct($location);
	}
	
	
	public function index()
	{
		
		$companies = $this->company->orderBy('companyname')->pluck('companyname','id');
		
		return response()->view('locations.index',compact('companies'));
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
		
		return response()->view('locations.create',compact('location', 'segments'));
	}

	/**
	 * Store a newly created location in storage.
	 *
	 * @return Response
	 */
	public function store(LocationFormRequest $request)
	{
		
		$location = $this->location->create($request->all());				
		$address = $request->get('street') . ",". $request->get('city') .",". $request->get('state')." ". $request->get('zip');
		$geoCode = app('geocoder')->geocode($address)->get();
		$data = $this->location->getGeoCode($geoCode);
		$location->update($data);
		
		return redirect()->route('locations.show',$location->id);
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
		return response()->view('locations.state', compact('location','filtered'));
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

		$location = $this->location
			->with('company','company.industryVertical','company.serviceline','relatedNotes','clienttype','verticalsegment')
			->findOrFail($id->id);
		

		//$this->getCompanyServiceLines($location);
	
		$branch = $this->findBranch(1,$location);

		$watch = $this->watch->where("location_id","=",$id->id)->where('user_id',"=",auth()->user()->id)->first();
		
	
		return response()->view('locations.show', compact('location','branch','watch'));
	}
	
	/*private function getCompanyServiceLines($location){

		foreach($location->company->serviceline as $serviceline){

			$servicelines[]=$serviceline->id;
		}
		$this->companyServicelines = implode("','",$servicelines);
		
	}*/





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
		
		return response()->view('locations.edit', compact('location'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(LocationFormRequest $request, $location)
	{
		

		$input = $request->only('businessname','street','city','state','zip','company_id','id','phone','contact','segment','businesstype');

		$location->update($request->all());
		$address = $input['street'] . ",". $input['city'] .",". $input['state']." ". $input['zip'];
		$geoCode = app('geocoder')->geocode($address)->get();
		$data = $this->location->getGeoCode($geoCode);
		$location->update($data);
		
		return redirect()->route('locations.show',$location->id );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($location)
	{
				
		$companyid = $location->company_id;
		$this->location->destroy($location->id);
		
		return redirect()->route('company.show',$companyid);
	}

	
	
	
	
	
	/**
	 * Used to find the closest branches to any location bsaed on servicelines
	 * @param  integer $limit number of branches to return
	 * @return object         [description]
	 */
	private function findBranch($limit = 5,$location) {
		foreach($location->company->serviceline as $serviceline){
			$userservicelines[] = $serviceline->id;
		}

		return $this->branch->findNearbyBranches($location->lat,$location->lng,100,$limit,$userservicelines);
	
	}
	
	
	
	/**
	 * Return view of closest n branches
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getClosestBranch(Request $request,$id,$n=5)
	{
		
		if ($request->has('d')) {
			$this->distance = $request->get('d');
		}
		$data['location'] = $this->location->with('company','company.serviceline')->findOrFail($id);

		//$this->getCompanyServiceLines();
	
		$data['branch'] = $this->findBranch($n,$data['location']);
		

		return response()->view('branches.assign', compact('data'));

		
	}

	public function showLocationsNearbyBranches(Request $request,$id)
	{	
		if ($request->has('d')) {
			$this->distance = $request->get('d');
		}
		$data['location'] = $this->location->with('company','company.serviceline')->findOrFail($id);
		//$this->getCompanyServiceLines();
		$branches= $this->findBranch(5,$data['location']);
		echo $this->branch->makeNearbyBranchXML($branches);

	}


	public function getClosestBranchMap($id,$n=5)
	{
		
		$location = $this->location->with('company','company.serviceline')->findOrFail($id);


		$servicelines = Serviceline::all();
		return response()->view('branches.nearbymap', compact('location','servicelines'));

	}


	public function showNearbyLocations(Request $request)
	{
		
		if ($request->has('d')) {
			$data['distance'] = $request->get('d');
		}else{
			$data['distance'] = '50';
		}
		
		//$data['branches'] = $this->branch->findOrFail($id);

		

		return response()->view('locations.nearby', compact('data'));
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

		// I dont understand this!
		//$data['manager'] = ! isset($branches->manager) ? array() : Person::find($data['branch']->person_id);

		$data['branch'] = $branches;
		$data['title']='National Accounts';
		$locations  = $this->getNearbyLocations($branches->lat,$branches->lng);
		$watchlist = User::where('id','=',auth()->user()->id)->with('watching')->get();
		foreach($watchlist as $watching) {
			foreach($watching->watching as $watched) {
				$mywatchlist[]=$watched->id;
			}
		}

		return response()->view('branches.showlist', compact('data','locations','mywatchlist','filtered'));
	}
		
	
	private function getNearbyLocations($lat=NULL,$lng=NULL,$distance=NULL,$company_id = null,$vertical=null)
	
	{
		
		if(! $distance){
			$distance ='10';
		}
		
		if (isset($lat) && isset($lng)){
			$loclat = $lat;
			$loclng = $lng;
		}else{
			$loclat = '47.25';
			$loclng = '-122.44';
		}
		
		$result = $this->location->findNearbyLocations($loclat,$loclng,$distance,$number=1,$company_id,$this->userServiceLines,$vertical);
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
		return response()->view('locations.notes',compact('notes','fields'));
	}	
	
	
	public function bulkImport(LocationImportFormRequest $request) {
		

		if($request->has('segment'))
		{
			$request->get('segment');
		}else{
			$segment = NULL;
		}	
		
		$company_id = $request->get('company');

		
		// Make sure its a CSV file
		$file = $request->file('upload');
		
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

			return redirect()->back()->withErrors(['Invalid file format.  Check the fields:<br />', array_diff($this->location->fillable,$data)]);
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
		

		return redirect()->to('/company/'.$company_id);
	}
	
	private function executeQuery($query)
	{
		
		$results = \DB::statement($query);
	}
	
	public function bulkGeoCodeLocations()
	{
		$locations = $this->location
		->where(['lat'=>NULL,'geostatus'=>TRUE])
		->orWhere(['lat'=>'0','geostatus'=>TRUE])
		->get();
		
		$n =0;
		foreach ($locations as $location) {
			
			if($n > $this->limit)
			{
				sleep($this->waitSeconds);	
				$n = 0;
			}
			$n++;
			$address = $location->street . ",". $location->city .",". $location->state." ". $location->zip;
			$geoCode = app('geocoder')->geocode($address)->get();
			$data = $this->location->getGeoCode($geoCode);
			$location->update($data);
			
		}
	
		echo "All done!";
	}
	
	
}