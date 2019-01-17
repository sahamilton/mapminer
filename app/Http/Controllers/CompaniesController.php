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
	public $limit = 500;
	public $NAMRole =['4'];


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

		$companies = $this->getAllCompanies($filtered)->get();
		$title = 'All Accounts';
		$locationFilter = 'both';

		return response()->view('companies.index', compact('companies','title','filtered','locationFilter'));
	}

	/*
	Function filter

	 * Returns list of companies based on selection: with or without locations
	 *
	 * @return Response
	 */


	public function filter(Request $request){


		if(request('locationFilter')=='both'){

			return redirect()->route('company.index');
		}
		$filtered = $this->company->isFiltered(['companies'],['vertical']);
		$companies=$this->getAllCompanies($filtered);


		if(request('locationFilter') == 'nolocations'){

			$companies = $companies->whereDoesntHave('locations')->get();

			$title = 'Accounts without Locations';

		}else{
			$companies = $companies->whereHas('locations')->get();

			$title = 'Accounts with Locations';

		}

		$locationFilter = request('locationFilter');

		return response()->view('companies.index', compact('companies','title','filtered','locationFilter'));

	}

	public function getAllCompanies($filtered=null)
	{

		$keys=array();

		$companies = $this->company
			->with('managedBy','managedBy.userdetails','industryVertical','serviceline','countlocations')
			->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

			});

		if($filtered) {
			$keys = $this->company->getSearchKeys(['companies'],['vertical']);
			$isNullable = $this->company->isNullable($keys,NULL);
			$companies = $companies->whereIn('vertical',$keys);

			if($isNullable == 'Yes')
			{

					$companies = $companies->orWhere(function($query) use($keys)
					{
						$query->whereNull('vertical');
					});

			}

		}

		return $companies->orderBy('companyname');

	}
	/**
	 * Show the form for creating a new company
	 *
	 * @return Response
	 */
	public function create()
	{

		$managers = $this->person->getPersonsWithRole($this->NAMRole);

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

		$company = $this->company->create(request()->all());
		$company->serviceline()->sync(request('serviceline'));

		return redirect()->route('company.index');
	}
	/**
	 * Show the form for editing the specified company.
	 *
	 * @param  int  $company id
	 * @return Response
	 */
	public function edit($company)
	{

		$managers = $this->person->getPersonsWithRole($this->NAMRole);
		
		$company = $company
					->where('id','=',$company->id)
					->with('managedBy')
					->with('serviceline')
					->firstOrFail();

		$servicelines = Serviceline::whereIn('id',$this->userServiceLines)
			->pluck('ServiceLine','id');

		$filters = $this->getFilters();

		//$verticals = $this->searchfilter->industrysegments();
		return response()->view('companies.edit', compact('company','managers','filters','servicelines'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $company
	 * @return Response
	 */
	public function update(CompanyFormRequest $request,$company)
	{


		$this->company = $company;

		$this->company->update( request()->all());
		$this->company->serviceline()->sync( request('serviceline'));

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

	public function show($id,$segment=null)
	{

		if(is_object($id)){
			$id = $id->id;

		}

		if (! $company = $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}

		$data = $this->getSegmentCompanyInfo($company,$segment);
		$company = $this->company->with('managedBy','industryVertical','type');
		if($filtered = $this->company->isFiltered(['companies'],['vertical'])){
				$keys = $this->company->getSearchKeys(['companies'],['vertical']);

					$company = $company
					->whereHas('industryVertical',function($q) use($keys){
						$q->whereIn('id',$keys);
					});

				}

		$company = $company->find($id);
		/*dd($company);
		if(count($company)!=1){
				return redirect()->route('company.index');
		}*/
		// get company locations

		$locations = $this->getCompanyLocations($id,$segment,$company);


		$states = $this->getStatesInArray($locations);
		$segments = $this->getCompanySegments($company);
		$filters = $this->searchfilter->vertical();
		$limited = null;
		$distance = null;
		$count = count($locations);

		// used when there are too many locations to show in list
		if( $count > $this->limit)
		{
		$location = $this->locations->getMyPosition();
		$distance = 1000;
		$locations = $this->locations
			->where('company_id','=',$company->id)
			->nearby($location,$distance,'10')
			->limit($this->limit)
			->get();
			$limited = count($locations);

		
		}
		
		$data['type']='company';
		$mywatchlist = $this->locations->getWatchList();


		return response()->view('companies.show', compact('data','company','locations','count','limited','mywatchlist','states','filtered','filters','segments','distance'));
	}


	private function getCompanyLocations($id,$segment,$company){
		$locations = $this->locations->where('company_id','=',$id);

		if($segment){

			$locations = $locations->where('segment','=',$segment);
		}
		$filtered = $company->isFiltered(['locations'],['segment','businesstype'],$company->vertical);
		$keys = $this->company->getSearchKeys(['locations'],['segment','businesstype']);


		// This doesnt make sense as long as companies belong to only one vertical


		if($filtered && count($keys)>0) {
			
			 $locations = $locations

				 ->whereIn('segment', $keys)
				 ->orWhere(function($query) use($data){

					$query->whereIn('businesstype', $data['keys']);
				});

		}
		
		 return $locations->orderBy('state')->get();

	}




	private function getStatesInArray($locations)
	{
		 return$locations->unique('state')->pluck('state')->toArray();

	}
	/*
	// Get all states that the company has locations in
	 */

	private function getCompanyStates($company,$data,$filtered) {

		$states = $this->locations->select('state')->distinct()
				->where('company_id','=',$company->id);

				if($filtered && count($data['keys'])>0){

					$states=$states->whereIn('segment', $data['keys'])
					->orWhere(function($query) use($data){

					$query->whereIn('businesstype', $data['keys']);
					});

				}
		return $states->orderBy('state')
				->pluck('state');


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

		$filtered = $this->company->isFiltered(['companies'],['vertical']);

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


	/**
	 * Display list of the locations of specified company in specified state.
	 *
	 * @param  int  $id
	 * @param text $state
	 * @return View
	 */


	public function stateselect(Request $request,$id=null,$state=null)
	{

		// The method can be used by either post or get routes

		if(request()->filled('id') && request()->filled('state')){
					$id = request('id');
					$state = urldecode(request('state'));


		}
		// Check if user can view company based on user serviceline
		// association.
		if (! $data['company'] =  $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}

		$data = $this->getStateCompanyInfo($data,$state);
		$segments=$this->getCompanySegments($data['company']);
		$filtered = $this->company->isFiltered(['companies'],['vertical']);

		if($filtered){
			$data['keys'] = $this->locations->getSearchKeys(['locations'],['segment','businesstype']);
		}
		$locations = $this->getStateLocations($data['company'],$state,$data,$filtered);
		$mywatchlist = $this->locations->getWatchList();

		$states= $this->getCompanyStates($data['company'],$data,$filtered);

		$filters= SearchFilter::all()->pluck('filter','id');
		return response()->view('companies.state', compact('data','filtered','locations','mywatchlist','states','filtered','filters','segments'));
	}

	/**
	 * Get locations of company in state information.
	 *
	 * @param  int  $id
	 * @param text $state
	 * @return array $locations
	 */

	private function getStateLocations($company,$state,$data,$filtered){

			$locations= $this->locations
			->where('state','=',$state)
			->where('company_id','=',$company->id);


			if($filtered && count($data['keys']) >0){
				$locations = $locations->whereIn('segment', $data['keys'])

						->orWhereIn('businesstype', $data['keys']);
			}

			return $locations->get();


	}

	/**
	* Get Company && State Meta information
	 * @param  int  $id
	 * @param text $state
	 * @return array $data
	*/
	private function getStateCompanyInfo($data,$state)
	{

		$state = trim($state);
		$statedata = State::where('statecode','=',$state)->first();

		$data['state']  = $statedata->fullstate;
		$data['statecode'] = $statedata->statecode;
		$data['lat'] = $statedata->lat;
		$data['lng'] = $statedata->lng;
		return $data;
	}

	/**
	 * Get company segments (location filters)
	 * @param  Integer $company Company ID
	 * @return Array    Company segments (filters)
	 */
	private function getCompanySegments($company)
	{

		$segments = array_keys($company->locations->groupBy('segment')->toArray());

	   return $this->searchfilter->whereIn('id',$segments)->pluck('filter','id')->toArray();
	}

	/**
	* Get State Meta information
	 * @param  int  $id
	 * @param text $state
	 * @return array $data
	*/
	private function getSegmentCompanyInfo($company,$segment)
	{

		if(! $segment){
			$data['segment']='All';
		}else{
			$data['segment']  = $this->searchfilter->select('filter')->findOrFail($segment)->filter;
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

		if (! $company = $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}
		$data = $this->getStateCompanyInfo($state);

		return response()->view('companies.statemap', compact('data'));
	}

	private function getFilters(){

		return SearchFilter::where('type','!=','group')
		->where('searchtable','=','companies')
		->where('searchcolumn','=','vertical')
		->orderBy('lft')
		->get();
		//$verticals->getLeaves()->where('searchcolumn','=','vertical');

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


	/*

	Export all account with manager details to Excel
	 */
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
	/*
	Generate the form to choose companies to download locations
	 */

	public function export(){
		$companies = $this->company
						->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})
						->orderBy('companyname')->pluck('companyname','id');

		return response()->view('locations.export',compact('companies'));
	}

	/*
	 * Function locationsexport
	 *
	 * Export locations of chosen company
	 *
	 * @param () none
	 * @return (array) mywatchlist
	 */
	public function locationsexport(Request $request) {


		$id = request('company');

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



}
