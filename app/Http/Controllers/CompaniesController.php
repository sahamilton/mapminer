<?php
namespace App\Http\Controllers;
use App\User;
use App\State;
use App\Person;
use App\Company;
use App\Location;
use App\Pagination;
use App\SearchFilter;
class CompaniesController extends BaseController {

	public $user;
	public $company;
	public $locations;
	public $searchfilter;
	public $userServiceLines;

	public function __construct(Company $company, Location $location, SearchFilter $searchfilter,User $user) {
		
		$this->company = $company;
		$this->locations = $location;
		$this->searchfilter = $searchfilter;
		$this->user = $user;
		parent::__construct();
		
		

	}
	
	
	/**
	 * Display a listing of companies
	 *
	 * @return View
	 */
	 
	public function index()
	{
		$this->userServiceLines = $this->company->currentUserServiceLines();

		//dd($this->userServiceLines);
		$filtered = $this->company->isFiltered(['companies'],['vertical']);

		$companies = $this->getAllCompanies($filtered);

		$title = 'All Accounts';

		$locationFilter = 'both';

		$fields = array('Company'=>'companyname','Manager'=>'manager','Email'=>'email','Vertical'=>'vertical','Service Lines'=>'serviceline','Has Locations'=>'locationcount');
		


		return response()->view('companies.index', compact('companies','fields','title','filtered','locationFilter'));
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
				->with('managedBy','industryVertical')
				->whereHas('serviceline', function($q){
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
				->orderBy('companyname')
				->get();
			}else{
				$companies = $this->company
				->whereIn('vertical',$keys)
				->with('managedBy','managedBy.userdetails','industryVertical')
				->whereHas('serviceline', function($q){
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
				->orderBy('companyname')
				->get();
			}
			
		}else{
			
			
			$companies = $this->company
			->with('managedBy','managedBy.userdetails','industryVertical','countlocations')
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
		$roles = ['4'];
		$managers = $this->getManagers($roles);
		$searchFilters = $this->searchfilter;
		$filters = $searchFilters->where('searchtable','=','companies')->pluck('filter', 'id');
		$servicelines = Serviceline::whereIn('id',$this->userServiceLines)
			->pluck('ServiceLine','id');

		return response()->view('companies.create',compact('managers','filters','servicelines'));
	}

	/**
	 * Store a newly created company in storage.
	 *
	 * @return Response
	 */
	
	public function store()
	{
		

		if(! $this->company->isValid($input = \Input::all())){
			return \Redirect::back()->withInput()->withErrors($this->company->errors);
		}
		
		$input['person_id'] = $this->getPersonId($input['user_id']);
		
		$this->company = $this->company->create($input);
		$this->company->serviceline()->attach(\Input::get('serviceline'));
		return \Redirect::route('company.index');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
	public function destroy($id)
	{
		$this->company->destroy($id);

		return \Redirect::route('company.index');
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
		$this->userServiceLines = $this->company->currentUserServicelines();
		if (! $this->company->checkCompanyServiceLine($company->id,$this->userServiceLines))
		{
			return \Redirect::route('company.index');
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
		
		
		$filters = SearchFilter::pluck('filter','id');
		
		
		
		// fields to display in the view
		
		$fields = array('Business Name'=>'businessname','Street'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Segment'=>'segment','Business Type'=>'businesstype');
		
		if (\Auth::user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}
		
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
		return response()->view('companies.show', compact('company','locations','mywatchlist','fields','states','filtered','filters','segments'));
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
			return \Redirect::route('company.index');
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

		
		$companies = $this->company
		->with('managedBy')
		->with('industryVertical')
		->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
		->where('vertical','=',$vertical)
		->orderBy('companyname')
		->get();
		

		$fields = array('Company'=>'companyname','Manager'=>'manager','Email'=>'email','Vertical'=>'vertical');
		

		return response()->view('companies.index', compact('companies','fields','title','filtered'));
		
	}
	
	public function locationFilter()
	{
			$filtered = $this->company->isFiltered(['companies'],['vertical']);
			$locationFilter= \Input::get('locationFilter');
			$companies = $this->getAllCompanies();
			$title = 'All Accounts';
			$fields = array('Company'=>'companyname','Manager'=>'manager','Email'=>'email','Vertical'=>'vertical','Service Lines'=>'serviceline','Has Locations'=>'locationcount',);
			return response()->view('companies.index', compact('companies','fields','title','filtered','locationFilter'));

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
			return \Redirect::route('company.index');
		}
		$filtered = $this->locations->isFiltered(['locations'],['segment','businesstype']);
		$keys = $this->locations->getSearchKeys(['locations'],['segment','businesstype']);
		
		$locations = $this->getStateLocations($id,$state);
		$segments = $this->getCompanySegments($id);
		$states= $this->getCompanyStates($id,$filtered,$keys);
		$data = $this->getStateCompanyInfo($id,$state);
		$fields = array('Business Name'=>'businessname',
						'Street'=>'street',
						'City'=>'city',
						'ZIP'=>'zip',
						'Contact'=>'contact',
						'Phone'=>'phone',
						'Watching'=>'watch');
		$mywatchlist = $this->getWatchList();
		if (\Auth::user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}
		
	
		return response()->view('companies.state', compact('data','locations','mywatchlist','fields','states','filtered','segments'));
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
		$this->userServiceLines = $this->company->currentUserServiceLines();
		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return \Redirect::route('company.index');
		}
		
		$filtered = $this->locations->isFiltered(['locations'],['segment','businesstype']);
		$keys = $this->locations->getSearchKeys(['locations'],['segment','businesstype']);
		
		$locations = Location::where('segment', $segment)->where('company_id', $id)->get();
		$states= $this->getCompanyStates($id,$filtered,$keys);

		$data = $this->getSegmentCompanyInfo($id,$segment);
		
		$segments = $this->getCompanySegments($id);

		$fields = array('Business Name'=>'businessname',
						'Street'=>'street',
						'City'=>'city',
						'ZIP'=>'zip',
						'Contact'=>'contact',
						'Phone'=>'phone',
						'Watching'=>'watch');
		$mywatchlist = $this->getWatchList();
		if (\Auth::user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}
		
	
		return response()->view('companies.segment', compact('data','locations','mywatchlist','fields','states','filtered','segments'));
	}
	
	
	/**
	 * Display list of the locations of specified company in specified state.
	 *
	 * @param  int  $id
	 * @param text $state
	 * @return View
	 */
	 
	 
	public function stateselect()
	{
		$this->userServiceLines = $this->company->currentUserServiceLines();
		$id = \Input::get('id');
		// Check if user can view company based on user serviceline
		// association.
		if (! $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return \Redirect::route('company.index');
		}
		$state = trim(\Input::get('state'));
		
		$locations = $this->getStateLocations($id,$state);

		$data = $this->getStateCompanyInfo($id,$state);

		$segments=$this->getCompanySegments($id);

		$fields = array('Business Name'=>'businessname',
						'Street'=>'street',
						'City'=>'city',
						'ZIP'=>'zip',
						'Contact'=>'contact',
						'Phone'=>'phone',
						'Segment'=>'segment',
						'Business Type'=>'businesstype',
						'Watching'=>'watch');
		$mywatchlist = $this->getWatchList();

		if (\Auth::user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}

		$filtered = $this->locations->isFiltered(['locations'],['segment','businesstype'],$data['company']['vertical']);

		$keys = $this->locations->getSearchKeys(['locations'],['segment','businesstype']);
		$states= $this->getCompanyStates($id,$filtered,$keys);
		$filters= SearchFilter::all()->pluck('filter','id');
		return response()->view('companies.state', compact('data','locations','mywatchlist','fields','states','filtered','filters','segments'));
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
			return \Redirect::route('company.index');
		}
		$data = $this->getStateCompanyInfo($id,$state);

		return response()->view('companies.statemap', compact('data'));
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
					->get();
		$servicelines = Serviceline::pluck('ServiceLine','id');
		$filters = SearchFilter::where('searchtable','=','companies')->pluck('filter', 'id');
		
		return response()->view('companies.edit', compact('company','managers','filters','servicelines'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($company)
	{
		
		$this->company = $company;
		$input = \Input::only('companyname','vertical','id','user_id','serviceline');
		$input['person_id'] = $this->getPersonID($input['user_id']);
		if(! $this->company->isValid($input)){
			return \Redirect::back()->withInput()->withErrors($this->company->errors);
		}
		$this->company->update($input);
		$servicelines = $input['serviceline'];
		$this->company->serviceline()->sync($servicelines);
		return \Redirect::route('company.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 
	

	public function delete($company)
	{
		
		$this->company = $this->company->findOrFail($company);
		
		$this->company->destroy($company);

		return \Redirect::route('company.index');
	}
	
	
	
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
		$data = $this->company
				->with(['industryVertical','managedBy'])
				->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})
				->get();
		
		$fields =['id','companyname', 'vertical',['industryVertical'=>'filter'],'person_id',['managedBy'=>'lastname']];
		
		$filename = 'accounts';
		$path = public_path() . "/downloads/";
		
		$results = $this->company->export ($fields,$data,$filename);
		
		return Response::make(rtrim($results['output'], "\n"), 200, $results['headers']);
		
		
		
		
	}
	
	
	public function export(){
		$companies = 	$this->company
						->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})
						->orderBy('companyname')->pluck('companyname','id');
		return response()->view('locations.export',compact('companies'));
	}
	
	public function locationsexport() {
		
		$id = \Input::get('company');
		$company = 	$this->company
					->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})
					->where('id','=',$id)->get();
		$results = $this->exportfile($company);
		return Response::make(rtrim($results['output'], "\n"), 200, $results['headers']);
		
	}
	/*
	 * Function exportfile
	 *
	 * Create array of locations of logged in users watchlist
	 *
	 * @param $company collection
	 * @return response file csv
	 */
	
	private function exportfile($company)
	{
		//Check that user can view company 
		// based on user service line associations.
		
		if (! $this->company->checkCompanyServiceLine($company[0]['id'],$this->userServiceLines))
		{
			return \Redirect::route('company.index');
		}
		$data = $this->locations->where('company_id','=',$company[0]['id'])->get();
		$fields =['id','businessname','street','suite','city','state','zip','company_id','lob','phone','contact','segment'];
		$selectfields = implode(",",$fields);
		$filename = $company[0]['companyname'] .'_locations.csv';
		$path = public_path() . "/downloads/";
		
		$results = $this->company->export ($fields,$data,$filename);
		return $results;
		
	}
	/**
	 * Return all people who have manager role
	 * @param  Array $roles 
	 * @return Array List of people who have role
	 */ 	 
	public function getManagers($roles)
	{
		
		$accountmanagers = $this->user->whereHas('roles', 
			function($q) use($roles){
			$q->whereIn('role_id',$roles);
			})->with('Person')->get();
		foreach ($accountmanagers as $manager) 
		{
			$managers[$manager->id] = $manager->person->firstname . " ". $manager->person->lastname;
		
		}
		return $managers;
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
	