<?php
namespace App\Http\Controllers;
use App\Watch;
use App\Branch;
use App\Company;
use App\User;
use Excel;
use App\Location;
use App\SearchFilter;
use App\Serviceline;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\LocationFormRequest;
use App\Http\Requests\LocationImportFormRequest;


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
	public $searchfilter;

	protected $branch;
	public function __construct(Location $location, Branch $branch, Company $company, Watch $watch, SearchFilter $filters){
		$this->location = $location;
		$this->watch = $watch;
		$this->company = $company;
		$this->branch = $branch;
		$this->searchfilter = $filters;
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
	
		$location = $this->company->findOrFail($accountID);
		//refactor Add company / segment relationship
		$segments = $this->searchfilter->segments();	
		$segments[null]='Not Specified';
		return response()->view('locations.create',compact('location', 'segments'));
	}

	/**
	 * Store a newly created location in storage.
	 *
	 * @return Response
	 */
	public function store(LocationFormRequest $request)
	{
				
		$address = $request->get('street') . ",". $request->get('city') .",". $request->get('state')." ". $request->get('zip');
		$data = $this->location->getGeoCode(app('geocoder')->geocode($address)->get());
		$request->merge($data);
		$location = $this->location->create($request->all());
		return redirect()->route('locations.show',$location->id)->with('message', 'Location Added');
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
			->with('company','company.industryVertical','company.serviceline','relatedNotes','clienttype','verticalsegment','contacts')
			->findOrFail($id->id);
		

		//$this->getCompanyServiceLines($location);
	
		$branch = $this->findBranch(1,$location);

		$watch = $this->watch->where("location_id","=",$id->id)->where('user_id',"=",auth()->user()->id)->first();
		
	
		return response()->view('locations.show', compact('location','branch','watch'));
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
		$address = $request->get('street') . ",". $request->get('city') .",". $request->get('state')." ". $request->get('zip');
		$data = $this->location->getGeoCode(app('geocoder')->geocode($address)->get());
		$request->merge($data);
		$location->update($request->all());

		return redirect()->route('locations.show',$location->id )->with('message','Location updated');
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
		
		return redirect()->route('company.show',$companyid)->with('message','Location deleted');
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

		//return $this->branch->findNearbyBranches($location->lat,$location->lng,100,$limit,$userservicelines);
		return $this->branch->nearby($location,'100')
			->whereHas('servicelines', function ($q) {
				$q->whereIn('servicelines.id',$this->userServiceLines);
			})
			->limit($limit)
			->get();
	
	}
	
	
	
	/**
	 * Return view of closest n branches
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getClosestBranch(Request $request,$id,$n=5)
	{
		
		if ($request->filled('d')) {
			$this->distance = $request->get('d');
		}
		$data['location'] = $this->location->with('company','company.serviceline')->findOrFail($id);

		//$this->getCompanyServiceLines();
	
		$data['branch'] = $this->findBranch($n,$data['location']);
		

		return response()->view('branches.assign', compact('data'));

		
	}

	public function getClosestBranchMap($id,$n=5)
	{
		
		$location = $this->location->with('company','company.serviceline')->findOrFail($id);

		$servicelines = Serviceline::all();
		return response()->view('branches.nearbymap', compact('location','servicelines'));

	}


	public function showNearbyLocations(Request $request)
	{
		
		if ($request->filled('d')) {
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
	
	// Why is this in locations? Should be in branches
	public function listNearbyLocations($id){
		
		
		$filtered = $this->location->isFiltered(['companies'],['vertical']);
		$roles = \App\Role::pluck('name','id');
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

		return response()->view('branches.showlist', compact('data','locations','mywatchlist','filtered','roles'));
	}
		
	
	private function getNearbyLocations($lat=NULL,$lng=NULL,$distance=NULL,$company_id = null,$vertical=null)
	
	{
		
		if(! $distance){
			$distance ='10';
		}
		$location=new \stdClass;

		if (isset($lat) && isset($lng)){
			$location->lat = $lat;
			$location->lng = $lng;
		}else{
			$locations->lat = '47.25';
			$locaction->lng = '-122.44';
		}
		
		//$result = $this->location->findNearbyLocations($loclat,$loclng,$distance,$number=1,$company_id,$this->userServiceLines,$vertical);
		$locations =  $this->location->nearby($location,$distance);	
		if($company_id){
			$locations->where('company_id','=',$company_id);
		}
		if($vertical){
			$locations->whereHas('company.industryVertical',function($q) use($vertical){
				$q->whereIn('searchfilter.id',$vertical);
			});
		}
		return $locations->whereHas('company.serviceline',function ($q) {
			$q->whereIn('servicelines.id',$this->userServiceLines);

		})->get();
		

		
	}
	
	
	public function bulkImport(LocationImportFormRequest $request) {
		

		if($request->filled('segment'))
		{
			$data['segment'] = $request->get('segment');
		}else{
			$data['segment'] = NULL;
		}	
		$data['company_id'] = $request->get('company');
		$file = $request->file('upload')->store('public/uploads');  
		$data['location'] = asset(Storage::url($file));
        $data['basepath'] = base_path()."/public".Storage::url($file);
        // read first line headers of import file
        $locations = Excel::load($data['basepath'],function($reader){
           
        })->first();

    	if( $this->location->fillable !== array_keys($locations->toArray())){
    		dd($this->location->fillable,array_keys($locations->toArray()));
    		
    		return redirect()->back()
    		->withInput($request->all())
    		->withErrors(['upload'=>['Invalid file format.  Check the fields:', array_diff($this->location->fillable,array_keys($locations->toArray())), array_diff(array_keys($locations->toArray()),$this->location->fillable)]]);
    	}

		$data['table'] ='locations';
		$data['fields'] = implode(",",array_keys($locations->toArray()));
		$this->location->importQuery($data);
		return redirect()->route('company.show',$data['company_id']);
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
	
	public function vcard($id){
			$vcard = new VCard;
			$location = $this->location
			->with('company')
			->findOrFail($id);
			
			$vcard->addName($location->contact,null,null,null,null);

			// add work data
			$vcard->addCompany($location->businessname);
			$vcard->addPhoneNumber($location->phone, 'PREF;WORK');
			$vcard->addAddress(null,$location->suite, $location->street, $location->city, null, $location->zip, null);
			$vcard->addURL(route('locations.show',$location->id));

			$vcard->download();

	}
	
}