<?php
namespace App\Http\Controllers;
use App\Serviceline;
use App\Address;
use App\Location;
use App\Company;
use App\Project;
use App\Branch;
use App\Watch;
use App\Lead;
use App\Person;
use App\Http\Requests\FindMeFormRequest;
use Illuminate\Http\Request;

class GeoCodingController extends BaseController {
	
	public $project;
	public $location;
	public $lead;
	public $branch;
	public $serviceline;
	public $person;
	public $address;


	public function __construct(
			Location $location, 
			Project $project, 
			Branch $branch, 
			Serviceline $serviceline,
			Person $person,
			Lead $lead,
			Address $address) 
	{
		$this->location = $location;
		$this->project = $project;
		$this->serviceline = $serviceline;
		$this->lead = $lead;	
		$this->branch = $branch;
		$this->person = $person;
		$this->address = $address;
		parent::__construct($location);	

}
	
	
	/**
	 * @return [type]
	 */

	/**  This needs some serious refactoring! **/

	public function findMe(FindMeFormRequest $request) {

	
		if(request()->filled('search')) {
				
			$address = urlencode(request('search'));
			
		}
		$data = request()->all();
		if($data['search'] != session('geo.search') or !session('geo.lat')){
			
			if(preg_match('^Lat:([0-9]*[.][0-9]*).Lng:([-]?[0-9]*[.][0-9]*)^', $data['search'],$string)){
				$data['lat']=$string[1];
				$data['lng'] = $string[2];
				$geocode = app('geocoder')->reverse($data['lat'],$data['lng'])->get();

				$data['search']= $geocode->first()->getFormattedAddress();
			}else{
			
				$geocode = app('geocoder')->geocode($data['search'])->get();
				//reset the geo session
				if(! $geocode or count($geocode)==0){

					return redirect()->back()->withInput()->with('error','Unable to Geocode address:'.request('address') );
				}
				
				request()->merge($this->location->getGeoCode($geocode));
				$data = request()->all();

			}
		}


		

		$data['latlng'] = $data['lat'].":".$data['lng'];
		// Kludge to address the issue of different data in Session::geo
		if(! request()->has('number')){

			$data['number']=5;
		}

		// we have to do this in case the lat / lng was set via the browser
		if(! isset($data['fulladdress'])){
			$data['fulladdress'] = $data['search'];
		}
		if(! request()->filled('addressType')){
			$data['addressType'] = ['customer','project','lead','location'];
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
		$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)
    						->get();

		if(isset($data['view']) && $data['view'] == 'list') {
		
			if($data['type']=='people'){
				return response()->view('maps.peoplelist', compact('data'));
			}

			if ($data['type']=='myleads'){
				$statuses = \App\LeadStatus::pluck('status','id')->toArray();
				return response()->view('myleads.index', compact('data','statuses'));
			}

			try {
				$watching = Watch::where('user_id',"=",\Auth::id())->get();
				foreach ($watching as $watch) {
					$watchlist[$watch->id] = $watch->location_id;
					
				}
			}
			catch (Exception $e) {
				$watchlist = NULL;
			}
			
			return response()->view('maps.list', compact('data','watchlist','filtered','company','servicelines'));
		}else{

			$data = $this->setZoomLevel($data);

			

    
			return response()->view('maps.map', compact('data','filtered','servicelines','company'));
		}
		
	}
	
	/**
	 * Get view data
	 * @param  array  $data
	 * @return array $data
	 */
	
	private function getViewData($data) {

		if(method_exists($this,'get'.ucwords($data['type']).'MapData')){
			$method = 'get'.ucwords($data['type']).'MapData';

			$data = $this->$method($data);

		}else{
			// get default map view
			$data= $this->getLocationMapData($data);
		}
		
		$data['datalocation']=$data['urllocation'] . '/'. $data['distance'].'/'.$data['latlng'];
		if($data['company']){
			$data['datalocation'].="/".$data['company']->id;
		}

		return $data;
	}

	private function getBranchMapData($data){
		$data['urllocation'] = "api/mylocalbranches";
		$data['title'] ='Branch Locations';
		$data['company']=NULL;
		$data['companyname']=NULL;
		return $data;
				

	}
	private function getLocationMapData($data){
		$data['urllocation'] ="api/address";
		$data['title'] ='Nearby Locations';
		$data['company']=NULL;
		$data['companyname']=NULL;
		return $data;
	}
	private function getCompanyMapData($data){
		$data['urllocation'] ="api/mylocalaccounts";
		$data['title'] = (isset($data['companyname']) ? $data['companyname'] : 'Company') ." Locations";
		$data['company'] = Company::where('id','=',$data['company'])->first();
		$data['vertical'] = $data['company']->vertical;
		return $data;

	}

	private function getProjectsMapData($data){
		$data['urllocation'] ="api/mylocalprojects";
		$data['title'] = "Project Locations";
		$data['company']=NULL;
		$data['companyname']=NULL;
		return $data;
	}

	private function getPeopleMapData($data){
		$data['urllocation'] ="api/mylocalpeople";
		$data['title'] = "People Locations";
		$data['company']=NULL;
		$data['companyname']=NULL;
		return $data;
	}

	private function getMyLeadsMapData($data){
		$data['urllocation'] ="api/myleads";
		$data['title'] = "Lead Locations";
		$data['company']=NULL;
		$data['companyname']=NULL;
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
		
		if(method_exists($this,'get'.ucwords($data['type']).'ListData')){
			$method = 'get'.ucwords($data['type']).'ListData';
			return $this->$method($location,$data,$company);

		}else{
			// get default map view
			$method = 'getLocationListData';
			return $this->$method($location,$data,$company);
		}
	

		
	}
	
	private function getBranchListData($location,$data){
		
		return $this->branch
			->whereHas('servicelines', function ($q) {
				$q->whereIn('servicelines.id',$this->userServiceLines);
			})
			->nearby($location,$data['distance'])
			->get();
	}

	private function getProjectsListData($location,$data){

		return $this->project
				->whereHas('source', function($q){
            		$q->where('status','=','open');
        		})
				->nearby($location,$data['distance'])
				->with('owner')
				->get();
			
	}

	private function getCompanyListData($location,$data,$company){
		return $this->location
				->where('company_id','=',$company->id)
				->nearby($location,$data['distance'])
				->with('company')
				->get();
	}

	private function getLocationListData($location,$data){
		
	
		return $this->address
		->whereIn('addressable_type',$data['addressType'])
		->nearby($location,$data['distance'])
				->with('company')
				
				->get();
	}

	private function getPeopleListData($location,$data){
		return $this->person
				->nearby($location,$data['distance'])
				->with('userdetails.roles')
				->get();
	}

	private function getMyLeadsListData($location,$data){
		
		return $this->lead->myLeads($statuses =[1,2],$all=true)
			->with('leadsource')
			->nearby($location,$data['distance'])
			->get();
	}
	
	/**
	 * Generate branches XML based on results
	 * @param  array  $result
	 * @return view	 */
	 
	public function getMyLocation(Request $request) {

		$filtered = $this->location->isFiltered(['locations'],['business','segment'],NULL);


		if(request()->filled('lat') && request()->filled('lng')) {
			
			$data = request()->all();

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
