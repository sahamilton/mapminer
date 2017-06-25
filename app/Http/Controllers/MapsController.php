<?php
namespace App\Http\Controllers;
use App\News;
use App\Branch;
use App\User;
use App\Location;
use Carbon\Carbon;
class MapsController extends BaseController {
	public $branch;
	public $location;
	/**
	 * Display a listing of regions
	 *
	 * @return Response
	 */
	public function __construct(Branch $branch, Location $location,User $user){
			$this->branch = $branch;
			$this->user = $user;
			$this->location = $location;
			parent::__construct($location);
	}
	
	/**
	 * [findMe description]
	 * @return [type] [description]
	 */
	public function findMe()
	{
		
			
			$user = $this->user->findOrFail(auth()->id());
			$nonews = $user->nonews;
			$now = date('Y-m-d h:i:s');
			
			if(! isset($nonews)){
		
				$nonews = Carbon::now('America/Vancouver')->subYear()->toDateTimeString();
				
				 
			}
			$news=News::where('startdate','>=',$nonews)
			->where('startdate','<=',$now)
			->where('enddate','>=',$now)
			->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
			->get();
			
			
			return view()->make('maps.showme',compact('news'));

	}
	
	
	public function getLocationsPosition($id)
	{
		
		$location = Location::findOrFail($id);

		$latlng = $location->lat.":".$location->lng;
	
		echo $this->findLocalBranches($distance='50',$latlng);
	}


	public function findLocalBranches($distance=NULL,$latlng = NULL) {
		
		$location =explode(":",$latlng);
		
		$branches = $this->branch->findNearbyBranches($location[0],$location[1],$distance,$number=null,$this->userServiceLines);

		return response()->view('maps.partials.branchxml', compact('branches'))->header('Content-Type', 'text/xml');
		
	}
	
	public function findLocalAccounts($distance=NULL,$latlng = NULL,$company = NULL) {
		
		
		$geo =explode(":",$latlng);

		$result = $this->location->findNearbyLocations($geo[0],$geo[1],$distance,$number=null,$company,$this->userServiceLines);
		return response()->view('locations.xml', compact('result'))->header('Content-Type', 'text/xml');


		
	}
	// hmmmmm I dont think this works~
	public function getCenterPoint()
	
	{
		return $centerPoint;	
	}
}