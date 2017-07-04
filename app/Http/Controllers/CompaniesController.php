<?php
namespace App\Http\Controllers;
use App\User;
use App\State;
use App\Person;
use App\Company;
use App\Location;
use Excel;
use App\Pagination;
use App\SearchFilter;
use App\Serviceline;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyFormRequest;
class CompaniesController extends BaseController {

	public $user;
	public $company;
	public $locations;
	public $searchfilter;
	public $person;


	public function __construct(Company $company, Location $location, SearchFilter $searchfilter,User $user,Person $person) {
		
		$this->company = $company;
		$this->locations = $location;
		$this->searchfilter = $searchfilter;
		$this->user = $user;
		$this->person = $person;
		parent::__construct($this->company);
	}
	
	
	/**
	 * Display a listing of companies
	 *
	 * @return View
	 */
	 
	public function index()
	{

		$filtered = $this->company->isFiltered(['companies'],['vertical']);

		$companies = $this->getAllCompanies($filtered);

		$title = 'All Accounts';

		$locationFilter = 'both';

		;
	
		
		return response()->view('companies.index', compact('companies','title','filtered','locationFilter'));
	}
	


	public function getAllCompanies($filtered=null)
	{
		
		$keys=array();
		if($filtered) {
			$keys = $this->company->getSearchKeys(['companies'],['vertical']);
			$isNullable = $this->company->isNullable($keys,NULL);
			if($isNullable == 'Yes')
			{
				$companies = $this->company->whereIn('vertical',$keys)
				->orWhere(function($query) use($keys)
				{
					$query->whereNull('vertical');
				})
				->with('managedBy','managedBy.userdetails','industryVertical','serviceline','countlocations')
				->whereHas('serviceline', function($q){
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
				->orderBy('companyname')
				->get();
			}else{
				$companies = $this->company
				->whereIn('vertical',$keys)
				->with('managedBy','managedBy.userdetails','industryVertical','serviceline','countlocations')
				->whereHas('serviceline', function($q){
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
				->orderBy('companyname')
				->get();
			}
			
		}else{
			

			$companies = $this->company
			->with('managedBy','managedBy.userdetails','industryVertical','countlocations','serviceline')
			->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
			->orderBy('companyname')
			->get();
		}

		return $companies;


	}
	/**
	 * Show the form for creating a new company
	 *
	 * @return Response
	 */
	public function create()
	{
		//this should be removed
	
		$roles = ['4'];
		$managers = $this->getManagers($roles);
		$filters = $this->getFilters();
		$servicelines = Serviceline::whereIn('id',$this->userServiceLines)
			->pluck('ServiceLine','id');

		return response()->view('companies.create',compact('managers','filters','servicelines'));
	}

	/**
	 * Store a newly created company in storage.
	 *
	 * @return Response
	 */
	
	public function store(CompanyFormRequest $request)
	{

		$company = $this->company->create($request->all());
		$company->serviceline()->sync($request->get('serviceline'));

		return redirect()->route('company.index');
	}
	/**
	 * Show the form for editing the specified company.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($company)
	{
		$roles = ['4'];
		$managers = $this->getManagers($roles);

		$company = $company
					->where('id','=',$company->id)
					->with('managedBy')
					->with('serviceline')
					->firstOrFail();

		$servicelines = Serviceline::whereIn('id',$this->userServiceLines)
			->pluck('ServiceLine','id');
		
		$filters = $this->getFilters();

		return response()->view('companies.edit', compact('company','managers','filters','servicelines'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(CompanyFormRequest $request,$company)
	{
		

		$this->company = $company;
		$this->company->update( $request->all());
		$this->company->serviceline()->sync( $request->get('serviceline'));
		return redirect()->route('company.index');
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
	public function destroy($company)
	{
		$this->company->destroy($company->id);

		return redirect()->route('company.index');
	}
	
	
	/**
	 * Display list of the locations of specified company.
	 *
	 * @param  int  $id
	 * @return View
	 */
	 
	public function show($company)
	{
		// Is the user permitted to see this company based on servicelines?
	

		if (! $this->company->checkCompanyServiceLine($company->id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}

		$mywatchlist = array();
		
		$filtered = $company->isFiltered(['locations'],['segment','businesstype'],$company->vertical);
		$keys = $this->company->getSearchKeys(['locations'],['segment','businesstype']);

		if($filtered) {	
			
			 $locations = \DB::table('locations')
				 ->where('company_id','=',$company->id)
				 ->whereIn('segment', $keys)
				 ->orWhereIn('businesstype', $keys)
				 ->orderBy('state')
				 ->get();

			
			
			
		}else{
			
			$locations = \DB::table('locations')
				 ->where('company_id','=',$company->id)
				 ->orderBy('state')
				 ->get();
			
			
		}
		
		$states = $this->getStatesInArray($locations);
		$segments = $this->getCompanySegments($company->id);
		//$locations = Paginator::make($locations, count($locations), '25');
		
		
		$filters = $this->searchfilter->vertical();
		
		
		
		$company = $this->company->where('id','=',$company->id)
		->with('industryVertical','serviceline')
		->with('managedBy')
		->first();

		if(count($locations) > 500)
		{
			if (\Session::has('geo'))
				{
					$geo = \Session::get('geo');
					$lat = $geo['lat'];
					$lng = $geo['lng'];
				}else{
					// use center of the country as default lat lng
					$lat = '47.25';
					$lng = '-122.44';

				}
			$count = count($locations);
			$locations = $this->locations->findNearbyLocations($lat,$lng,'1000',$number=null,$company->id,$this->userServiceLines, $limit = 500);
			
			
			return response()->view('companies.showselect',compact('count','company','locations','mywatchlist','fields','states','filtered','filters','segments'));
		}	

		$mywatchlist = $this->getWatchList();
		return response()->view('companies.show', compact('company','locations','mywatchlist','states','filtered','filters','segments'));
	}
	
	private function getStatesInArray($locations)
	{
		$states= array();
		
		foreach ($locations as $location)
		{
			if(! in_array($location->state,$states))
			{
				$states[]=$location->state;
			}
		}
		return $states;
	}
	
	
	private function getCompanyStates($id,$filtered=NULL,$keys=NULL) {
		
		// Chack that user has service line permission 
		// to view company
		
		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}
		// this needs to be filtered
		$states=array();
		if($filtered){
			
			$states =  \DB::table('locations')
				->select('state')
				->where('company_id','=',$id)
				->whereIn('segment', $keys)
				->orWhereIn('businesstype', $keys)
				->distinct()
				->orderBy('state')
				->pluck('state');

			
			
			
		}else{
			$states = \DB::table('locations')
				->select('state')
				->where('company_id','=',$id)
				->distinct()
				->orderBy('state')
				->pluck('state');
			
		}
		
		return $states;
		
	}

	/**
	 * Display list of the companies in specified vertical 
	 *
	 *
	 * @param text $vertical
	 * @return View
	 */
	public function vertical($vertical)
	{
		
		$filtered = FALSE;
		$verticalname = SearchFilter::where('id','=',$vertical)->pluck('filter');

		$title = 'All '. $verticalname[0]. ' Accounts';

		$locationFilter = 'both';
		$companies = $this->company
		->with('managedBy','managedBy.userdetails','industryVertical','serviceline','countlocations')
		->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
		->where('vertical','=',$vertical)
		->orderBy('companyname')
		->get();
		
		return response()->view('companies.index', compact('companies','title','filtered','locationFilter'));
		
	}
	
	public function locationFilter()
	{
			$filtered = $this->company->isFiltered(['companies'],['vertical']);
			$locationFilter= \Input::get('locationFilter');
			$companies = $this->getAllCompanies();
			$title = 'All Accounts';
			
			return response()->view('companies.index', compact('companies','title','filtered','locationFilter'));

	}
	
	 /**
	 * Display list of the locations of specified company in specified state.
	 *
	 * @param  int  $id
	 * @param text $state
	 * @return View
	 */
	public function state($id,$state)
	{
		// check if user can view company (id) 
		// based on serviceline	association

		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}
		$filtered = $this->locations->isFiltered(['locations'],['segment','businesstype']);
		$keys = $this->locations->getSearchKeys(['locations'],['segment','businesstype']);
		
		$locations = $this->getStateLocations($id,$state);
		$segments = $this->getCompanySegments($id);
		$states= $this->getCompanyStates($id,$filtered,$keys);
		$data = $this->getStateCompanyInfo($id,$state);
		
		$mywatchlist = $this->getWatchList();
		
	
		return response()->view('companies.state', compact('data','locations','mywatchlist','states','filtered','segments'));
	}
	
	
	
	 /**
	 * Display list of the locations of specified company in specified segment.
	 *
	 * @param  int  $id Company id
	 * @param text $state
	 * @return View
	 */
	public function segment($id,$segment)
	{
		// Check if user can view company based on user serviceline
		// association.

		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}
		
		$filtered = $this->locations->isFiltered(['locations'],['segment','businesstype']);
		$keys = $this->locations->getSearchKeys(['locations'],['segment','businesstype']);
		
		$locations = Location::where('segment', $segment)->where('company_id', $id)->get();
		$states= $this->getCompanyStates($id,$filtered,$keys);

		$data = $this->getSegmentCompanyInfo($id,$segment);
		
		$segments = $this->getCompanySegments($id);

		
		$mywatchlist = $this->getWatchList();
		
	
		return response()->view('companies.segment', compact('data','locations','mywatchlist','states','filtered','segments'));
	}
	
	
	/**
	 * Display list of the locations of specified company in specified state.
	 *
	 * @param  int  $id
	 * @param text $state
	 * @return View
	 */
	 
	 
	public function stateselect(Request $request)
	{

		$id = $request->get('id');
		// Check if user can view company based on user serviceline
		// association.
		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}
		$state = trim($request->get('state'));
		
		$locations = $this->getStateLocations($id,$state);

		$data = $this->getStateCompanyInfo($id,$state);

		$segments=$this->getCompanySegments($id);


		$mywatchlist = $this->getWatchList();

		
		$filtered = $this->locations->isFiltered(['locations'],['segment','businesstype'],$data['company']['vertical']);

		$keys = $this->locations->getSearchKeys(['locations'],['segment','businesstype']);
		$states= $this->getCompanyStates($id,$filtered,$keys);
		$filters= SearchFilter::all()->pluck('filter','id');
		return response()->view('companies.state', compact('data','locations','mywatchlist','states','filtered','filters','segments'));
	}
	
	/**
	 * Get company and state meta information.
	 *
	 * @param  int  $id
	 * @param text $state
	 * @return array $locations
	 */
	
	private function getStateLocations($id,$state){
		
			$company = $this->company->with('industryVertical')
			->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
			->where('id','=',$id)
			->get();

			$filtered = $this->company->isFiltered(['locations'],['segment','businesstype'],$company[0]->industryVertical);
			
			$keys = $this->locations->getSearchKeys(['locations'], ['segment','businesstype']);

			$locations = \DB::table('locations')
				 ->where('company_id','=',$id)
				 ->where('state','=',$state)
				// ->whereIn('segment', $keys)
				// ->orWhereIn('businesstype', $keys)
				 ->orderBy('state')
				 ->get();
		
			//$locations = $this->company->getFilteredLocations($filtered, $keys,$query,$paginate=NULL);
			
			return $locations;
	}
	
	/**
	* Get State Meta information 
	 * @param  int  $id
	 * @param text $state
	 * @return array $data
	*/
	private function getStateCompanyInfo($id,$state) 
	{

		$data['company'] = $this->company
					->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
					->findOrFail($id);
		$statedata = State::where('statecode','=',$state)->get();

		$data['id'] =$id;
		foreach ($statedata as $state) {
			$data['state']  = $state->fullstate;
			$data['statecode'] = $state->statecode;
			$data['lat'] = $state->lat;
			$data['lng'] = $state->lng;
			
		}

		return $data;
		
	}
	
	/**
	 * Get company segments (location filters)
	 * @param  Integer $company Company ID
	 * @return Array    Company segments (filters)
	 */
	private function getCompanySegments($company)
	{
		$segments = \DB::select(\DB::raw("SELECT 
		distinct searchfilters.id, filter 
		FROM `searchfilters`,locations 
		WHERE company_id = ". $company. " 
			and searchcolumn = 'segment' 
			and segment = searchfilters.id
		ORDER BY filter"));
			
	return $segments;	
	}
	
	/**
	* Get State Meta information 
	 * @param  int  $id
	 * @param text $state
	 * @return array $data
	*/
	private function getSegmentCompanyInfo($id,$segment) 
	{

		$data['company'] = $this->company->findOrFail($id);
		
		$segmentdata = SearchFilter::where('id','=',$segment)->get();

		$data['id'] =$id;
		foreach ($segmentdata as $segment) {
			$data['segment']  = $segment->filter;
			
			
		}
		return $data;
		
	}
	
	
	/**
	 * Display map of the locations of specified company in specified state.
	 *
	 * @param  int  $id
	 * @param text $state
	 * @return View
	 */
	public function statemap($id,$state)
	{
		
		// Check that user can view company 
		// based on user serviceline association
		
		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}
		$data = $this->getStateCompanyInfo($id,$state);

		return response()->view('companies.statemap', compact('data'));
	}
	
	
	
	
	


	private function getFilters(){
		$verticals = SearchFilter::where('type','=','group')
		->where('searchtable','=','companies')
		->first();
		return $verticals->getLeaves()->where('searchcolumn','=','vertical');
	}
	

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 
	

	
	
	
	
	/*
	 * Function getWatchList
	 *
	 * Create array of locations of logged in users watchlist
	 *
	 * @param () none
	 * @return (array) mywatchlist
	 */
	public function getWatchList() {
		$mywatchlist = array();
		$watchlist = $this->user->where('id','=',\Auth::id())->with('watching')->get();
		foreach($watchlist as $watching) {
			foreach($watching->watching as $watched) {
				$mywatchlist[]=$watched->id;
			}
		}
		return $mywatchlist;
	}
	
	public function exportAccounts()
	{
		
		Excel::create('AllCompanies',function($excel){
			$excel->sheet('Companies',function($sheet) {
				$companies = $this->company

				->with('industryVertical','managedBy','serviceline')

				->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})
				->get();
		
				$sheet->loadview('companies.exportcompanies',compact('companies'));
			});
		})->download('csv');
		
	}
	
	
	public function export(){
		$companies = $this->company
						->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})
						->orderBy('companyname')->pluck('companyname','id');

		return response()->view('locations.export',compact('companies'));
	}
	
	public function locationsexport(Request $request) {
		
		$id = $request->get('company');
		$company = $this->company->findOrFail($id);
		Excel::create($company->companyname. " locations",function($excel) use($id){
			$excel->sheet('Watching',function($sheet) use($id) {
				$company = 	$this->company
					->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})
					
					->with('locations')
					->findOrFail($id);
				$sheet->loadview('locations.exportlocations',compact('company'));
			});
		})->download('csv');
		
		
		
	}
	
	/**
	 * Return all people who have manager role
	 * @param  Array $roles 
	 * @return Array List of people who have role
	 */ 	 
	public function getManagers($roles)
	{
		
		 return Person::select(\DB::raw('concat(firstname," ",lastname) as name,id'))
			->whereHas('userdetails.roles', 
			function($q) use($roles){
			$q->whereIn('role_id',$roles);
			})
			->orderBy('lastname')
			->pluck('name','id');


	}
	
	/**
	 * Return associated person (profile) information 
	 * based on user id
	 * @param  Integer $id User id
	 * @return Integer $personID     Person Id
	 */
	private function getPersonID($id)
	{
		$personID = $this->person->where('user_id','=',$id)->pluck('id');

		return $personID[0];
	}
	
	

	/**
	 * Seeder - Create default user, comapny & news serviceline assignements.
	 * @return none
	 */
	public function seeder()
	{

		$servicelines=[1,2,3];
		$companies=$this->company
				->has('serviceline', '<', 1)
				->select('id')->get();

				foreach ($companies as $company)
				{
					$nextcompany = $this->company->with('serviceline')->findOrFail($company->id);
					
					$nextcompany->serviceline()->attach($servicelines);

				}
		echo "All Companies Linked<br />";
		$users=$this->user->select('id')->has('serviceline', '<', 1)->get();

				foreach ($users as $user)
				{
					$nextuser = $this->user->with('serviceline')->findOrFail($user->id);
					$nextuser->serviceline()->attach($servicelines);

				}
		echo "All Users Linked<br />";

		$news=News::select('id')->has('serviceline', '<', 1)->get();

				foreach ($news as $update)
				{
					$nextupdate = News::with('serviceline')->findOrFail($update->id);
					$nextupdate->serviceline()->attach($servicelines);

				}
		echo "All News Linked<br />";

	}
}
	