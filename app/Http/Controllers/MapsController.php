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
	
		$result = $this->branch->findNearbyBranches($location[0],$location[1],$distance,$number=5,$this->userServiceLines);
		echo $this->branch->makeNearbyBranchXML($result);
		
	}
	
	public function findLocalAccounts($distance=NULL,$latlng = NULL,$company = NULL) {
		
		
		$geo =explode(":",$latlng);

		
		$result = $this->location->findNearbyLocations($geo[0],$geo[1],$distance,$number=1,$company,$this->userServiceLines);
		$content = view('locations.xml', compact('result'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
		//$this->location->makeNearbyLocationsXML($result);
		
	}
	// hmmmmm I dont think this works~
	public function getCenterPoint()
	
	{
		return $centerPoint;	
	}
}