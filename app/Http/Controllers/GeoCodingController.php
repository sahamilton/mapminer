<?php
namespace App\Http\Controllers;
use App\Serviceline;
use App\Location;
use App\Company;
use App\Project;
use App\Branch;
use App\Watch;
use App\Person;
use App\Http\Requests\FindMeFormRequest;
use Illuminate\Http\Request;

class GeoCodingController extends BaseController {
	
	public $project;
	public $location;
	public $branch;
	public $serviceline;


	public function __construct(Location $location, Project $project, Branch $branch, Serviceline $serviceline) {
		$this->location = $location;
		$this->project = $project;
		$this->serviceline = $serviceline;	
		$this->branch = $branch;
		parent::__construct($location);	
}
	
	
	/**
	 * @return [type]
	 */
	public function findMe(FindMeFormRequest $request) {

		if($request->filled('address')) {
			$address = urlencode($request->get('address'));
			
		}
		if(! $request->filled('lat')){
			$geocode = app('geocoder')->geocode($request->get('address'))->get();

			if(! $geocode or count($geocode)==0){

				return redirect()->back()->withInput()->with('error','Unable to Geocode address:'.$request->get('address') );
			}
			
			$request->merge($this->location->getGeoCode($geocode));
			
		
		}
		$data = $request->all();

		$data['latlng'] = $data['lat'].":".$data['lng'];
		// Kludge to address the issue of different data in Session::geo
		if(! $request->has('number')){
			$data['number']=5;
		}
		// we have to do this in case the lat / lng was set via the browser
		if(! isset($data['fulladdress'])){
			$data['fulladdress'] = $data['address'];
		}
		session()->put('geo', $data);
	
		$watchlist = array();
		$data['vertical'] = NULL;
		
		$data = $this->getViewData($data);


		$filtered = $this->location->isFiltered(['companies','locations'],['vertical','business','segment'],NULL);
		if(isset($data['company']) ){
    		$company = $data['company'];
    	}else{
    		$company=null;
    	}
    	$data['result'] = $this->getGeoListData($data);
    	if(count($data['result'])==0){
			
			session()->flash('warning','No results found. Consider increasing your search distance');

		}
		if(isset($data['view']) && $data['view'] == 'list') {
		
			

			try {
				$watching = Watch::where('user_id',"=",\Auth::id())->get();
				foreach ($watching as $watch) {
					$watchlist[$watch->id] = $watch->location_id;
					
				}
			}
			catch (Exception $e) {
				$watchlist = NULL;
			}
		
			return response()->view('maps.list', compact('data','watchlist','filtered','company'));
		}else{

			$data = $this->setZoomLevel($data);

			$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)
    						->get();
    			
			return response()->view('maps.map', compact('data','filtered','servicelines','company'));
		}
		
	}
	
	/**
	 * Get view data
	 * @param  array  $data
	 * @return array $data
	 */
	
	private function getViewData($data) {

		if($data['type'] =='branch'){

				$data['urllocation'] = "api/mylocalbranches";
				$data['title'] ='Branch Locations';
				$data['company']=NULL;
				$data['companyname']=NULL;
				
			
			}elseif ($data['type']=='company'){
				
				$data['urllocation'] ="api/mylocalaccounts";
				$data['title'] = (isset($data['companyname']) ? $data['companyname'] : 'Company') ." Locations";
				$data['company'] = Company::where('id','=',$data['company'])->first();
				$data['vertical'] = $data['company']->vertical;

			}elseif ($data['type'] == 'projects'){
				$data['urllocation'] ="api/mylocalprojects";
				$data['title'] = "Project Locations";
				$data['company']=NULL;
				$data['companyname']=NULL;
			
			}else{

				$data['urllocation'] ="api/mylocalaccounts";
				$data['title'] ='National Account Locations';
				$data['company']=NULL;
				$data['companyname']=NULL;
			}
			$data['datalocation']=$data['urllocation'] . '/'. $data['distance'].'/'.$data['latlng'];
			if($data['company']){
				$data['datalocation'].="/".$data['company']->id;
			}

			return $data;
	}
	
	/**
	 * Add map zoom level to data array
	 * @param  array  $data
	 * @return array $data
	 */
	
	private function setZoomLevel ($data) {
		
		$levels = \Config::get('app.zoom_levels');
		$data['zoomLevel']='10';
		if(isset($data['distance']) && array_key_exists($data['distance'],$levels)) {
			$data['zoomLevel']= $levels[$data['distance']];
		}else{
			$data['distance'] = '10';
		}
		return $data;
	}
	
	/**
	 * Find locations or branches based on location and distance
	 * @param  array  $data
	 * @return array $result
	 */
	 
	public function getGeoListData($data ) {
		$company = isset($data['company']) ? $data['company'] : NULL;
		$location = new Location;
		$location->lat = $data['lat'];
		$location->lng = $data['lng'];

		switch ($data['type']) {
			
			case 'location':
			case 'company':
			if($company){
				
				return $this->location
				->where('company_id','=',$company->id)
				->nearby($location,$data['distance'])
				->with('company')
				->get();
			}else{
			
				return $this->location
				->whereHas('company.serviceline', function ($q) {
						$q->whereIn('servicelines.id',$this->userServiceLines);
				})->nearby($location,$data['distance'])
				->with('company')
				->get();
			}
			
			
			
			break;
			
			case 'branch':

			return $this->branch
			->whereHas('servicelines', function ($q) {
				$q->whereIn('servicelines.id',$this->userServiceLines);
			})
			->nearby($location,$data['distance'])
			->get();
			
			break;

			case 'projects':
				
				return $this->project
				->whereHas('source', function($q){
            		$q->where('status','=','open');
        		})
				->nearby($location,$data['distance'])
				->with('owner')
				->get();
			
			break;
			
			default:
			if($company){
				return $this->location
				->where('company_id','=',$company)
				->nearby($location,$data['distance'])
				->get();
			}else{
				return $this->location
				->nearby($location,$data['distance'])
				->get();
			}
		
			break;
		}
		
	}
	
	
	/**
	 * Generate branches XML based on results
	 * @param  array  $result
	 * @return view	 */
	 
	public function getMyLocation(Request $request) {

		$filtered = $this->location->isFiltered(['locations'],['business','segment'],NULL);

		if($request->filled('lat') && $request->filled('lng')) {
			
			$data = $request->all();
			$data['latlng'] = $data['lat'].":".$data['lng'];
			if($data['type'] == 'list') {
				$data['result'] = $this->getGeoListData($data);
				
				return response()->view('maps.list', compact('data','filtered'));
			}else{
				$data = $this->setZoomLevel($data);
				if($data['view'] =='branch'){
					$data['urllocation'] = "api/mylocalbranches";
				}else{
					$data['urllocation'] ="api/mylocalaccounts";
				}
				$filtered = $this->location->isFiltered(['locations'],['business','segment']);
				return response()->view('maps.map', compact('data','filtered'));
			}
		}else{
			return response()->view('maps.form');
			
		}
		
	}

	 
}