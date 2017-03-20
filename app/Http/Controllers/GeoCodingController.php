<?php
namespace App\Http\Controllers;
use App\Serviceline;
use App\Location;
use App\Company;
use App\Branch;
use App\Watch;

class GeoCodingController extends BaseController {
	
	
	public $location;
	public $branch;
	public $serviceline;

	public function __construct(Location $location, Branch $branch, Serviceline $serviceline) {
		$this->location = $location;
		$this->serviceline = $serviceline;	
		$this->branch = $branch;
		parent::__construct();	
}
	
	
	/**
	 * @return [type]
	 */
	public function findMe() {

		$data = \Input::all();

		$rules = array('address'=>array('required'));

		$validation = \Validator::make($data,$rules);
		if($validation->fails()){
			return \Redirect::back()->withErrors($validation)->withInput();
		}
		
		
		//$data['address'] = NULL;
		if(isset($data['address'])) {
			$address = urlencode($data['address']);
			
		}
		if(! $data['lat']){

			$geocode = \Geocoder::geocode($address)->get();
			
			if(! $geocode){

				return redirect()->back()->withInput()->with('message', 'Unable to Geocode that address');
			}
			

				$data['lat']=$geocode[0]['latitude'];
				$data['lng'] =$geocode[0]['longitude'];
			
			
			
		}
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
			$fields = ['Business Name'=>'businessname','Street'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Watching'=>'watch'];
			
			return response()->view('maps.list', compact('data','watchlist','fields','filtered'));
		}else{
			$this->userServiceLines = $this->serviceline->currentUserServicelines();
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
		$this->userServiceLines = $this->serviceline->currentUserServicelines();;
		
		switch ($data['type']) {
			
			case 'location':
			
			return $result = $this->location->findNearbyLocations($data['lat'],$data['lng'],$data['distance'],$number=1,$company,$this->userServiceLines);
			
			break;
			
			case 'branch':

			return $result = $this->branch->findNearbyBranches($data['lat'],$data['lng'],$data['distance'],$number=1,$this->userServiceLines);
			
			
			break;
			
			default:
			return $result = $this->location->findNearbyLocations($data['lat'],$data['lng'],$data['distance'],$number=1,$company,$this->userServiceLines);
			
			
			
			break;
		}
		
	}
	
	/**
	 * Generate branches XML based on results
	 * @param  array  $result
	 * @return XML $dom
	 */
	
	/*
	
	 private function makeMapXML($result) {
		if (App::environment() == 'local'){
			\Debugbar::disable();
		}
		$dom = new \DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		foreach($result['manages'] as $row){
		  // ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("name",trim($row['branchname']));
			$newnode->setAttribute("address", $row['street']." ". $row['city']." ". $row['state']);
			$newnode->setAttribute("lat", $row['lat']);
			$newnode->setAttribute("lng", $row['lng']);
			$newnode->setAttribute("locationweb",route('branch.show' , $row['id']) );
			$newnode->setAttribute("id", $row['id']);	
			$newnode->setAttribute("type", 'branch');	
		}
		return $dom->saveXML();
	}
	*/
	
	/**
	 * Generate branches XML based on results
	 * @param  array  $result
	 * @return view	 */
	 
	public function getMyLocation() {
		$filtered = $this->location->isFiltered(['locations'],['business','segment'],NULL);

		if(\Input::get('lat') && \Input::get('lng')) {
			
			$data = \Input::all();
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
?>