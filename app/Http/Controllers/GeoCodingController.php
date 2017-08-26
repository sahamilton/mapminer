<?php
namespace App\Http\Controllers;
use App\Serviceline;
use App\Location;
use App\Company;
use App\Project;
use App\Branch;
use App\Watch;
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
	

		if($request->has('address')) {
			$address = urlencode($request->get('address'));
			
		}
		if(! $request->has('lat')){
			$geocode = app('geocoder')->geocode($request->get('address'))->get();

			if(! $geocode or count($geocode)==0){

				return redirect()->back()->withInput()->with('error','Unable to Geocode address:'.$request->get('address') );
			}
			
			$request->merge($this->location->getGeoCode($geocode));
			
		
		}
		$data = $request->all();
		$data['latlng'] = $data['lat'].":".$data['lng'];
		
		\Session::put('geo', $data);

		$watchlist = array();
		$data['vertical'] = NULL;
		$data = $this->getViewData($data);

		$filtered = $this->location->isFiltered(['companies','locations'],['vertical','business','segment'],NULL);

		if($data['view'] == 'list') {
			
			$data['result'] = $this->getGeoListData($data);

			try {
				$watching = Watch::where('user_id',"=",\Auth::id())->get();
				foreach ($watching as $watch) {
					$watchlist[$watch->id] = $watch->location_id;
					
				}
			}
			catch (Exception $e) {
				$watchlist = NULL;
			}
			
			return response()->view('maps.list', compact('data','watchlist','filtered'));
		}else{

			$data = $this->setZoomLevel($data);
			$servicelines = $this->serviceline->whereIn('id',$this->userServiceLines)
    						->get();
    						
			return response()->view('maps.map', compact('data','filtered','servicelines'));
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
				$data['vertical'] = Company::where('id','=',$data['company'])->pluck('vertical');
			}elseif ($data['type'] == 'projects'){
				$data['urllocation'] ="api/mylocalprojects";
				$data['title'] = "Project Locations";
			}else{
				$data['urllocation'] ="api/mylocalaccounts";
				$data['title'] ='National Account Locations';
				$data['company']=NULL;
				$data['companyname']=NULL;
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
		if(array_key_exists($data['distance'],$levels)) {
			$data['zoomLevel']= $levels[$data['distance']];
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

	
		switch ($data['type']) {
			
			case 'location':
			case 'company':
			
			return $result = $this->location->findNearbyLocations($data['lat'],$data['lng'],$data['distance'],$number=null,$company,$this->userServiceLines);
			
			break;
			
			case 'branch':

			return $this->branch->findNearbyBranches($data['lat'],$data['lng'],$data['distance'],$number=null,$this->userServiceLines);
			
			
			break;

			case 'projects':
				return $this->project->findNearbyProjects($data['lat'],$data['lng'],$data['distance'],$number=null);

			
			break;
			
			default:
			return $result = $this->location->findNearbyLocations($data['lat'],$data['lng'],$data['distance'],$number=1,$company,$this->userServiceLines);
			
			
			
			break;
		}
		
	}
	
	
	/**
	 * Generate branches XML based on results
	 * @param  array  $result
	 * @return view	 */
	 
	public function getMyLocation(Request $request) {
		$filtered = $this->location->isFiltered(['locations'],['business','segment'],NULL);

		if($request->has('lat') && $request->has('lng')) {
			
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