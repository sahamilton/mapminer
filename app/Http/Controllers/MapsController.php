<?php
namespace App\Http\Controllers;
use App\News;
use App\Branch;
use App\User;
use App\Person;
use App\Location;
use Carbon\Carbon;
class MapsController extends BaseController {
	public $branch;
	public $location;
	public $news;
	public $person;
	/**
	 * Display a listing of regions
	 *
	 * @return Response
	 */
	public function __construct(Branch $branch, Location $location,User $user,News $news,Person $person){
			$this->branch = $branch;
			$this->user = $user;
			$this->news = $news;
			$this->person = $person;
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
				$nonews = now('America/Vancouver')->subYear()->toDateTimeString();
			}
			$news = $this->news->currentNews();
			$filtered = $this->location->isFiltered(['companies'],['vertical']);
			return view()->make('maps.showme',compact('news','filtered'));

	}
	
	
	public function getLocationsPosition($id)
	{
		
		$location = Location::findOrFail($id);

		$latlng = $location->lat.":".$location->lng;
	
		echo $this->findLocalBranches($distance='50',$latlng);
	}


	public function findLocalBranches($distance=NULL,$latlng = NULL,$limit=null) {
		
		$location = $this->getLocationLatLng($latlng);
	
		$branches =  $this->branch
			->whereHas('servicelines',function ($q){
				$q->whereIn('servicelines.id',$this->userServiceLines);
			})
			->nearby($location,$distance,$limit)
			->get();
		
		return response()->view('branches.xml', compact('branches'))->header('Content-Type', 'text/xml');
		
	}
	public function findLocalPeople($distance=NULL,$latlng = NULL,$limit=null) {
		
		$location = $this->getLocationLatLng($latlng);
	
		$persons =  $this->person
			
			->nearby($location,$distance,$limit)
			->get();
		
		return response()->view('persons.xml', compact('persons'))->header('Content-Type', 'text/xml');
		
	}
	public function findLocalAccounts($distance=NULL,$latlng = NULL,$company = NULL) {
		
		$location = $this->getLocationLatLng($latlng);
		$locations = $this->location
		->whereHas('company.serviceline',function ($q){
			$q->whereIn('servicelines.id',$this->userServiceLines);
		});
		if($company){
			$locations->where('company_id','=',$company);
		}
		$result = $locations->nearby($location,$distance)->with('company')->get();
		return response()->view('locations.xml', compact('result'))->header('Content-Type', 'text/xml');
	
	}
	// hmmmmm I dont think this works~
	public function getCenterPoint()
	
	{
		return $centerPoint;	
	}

	private function getLocationLatLng($latlng){
		$position =explode(":",$latlng);
		$location = new Location;
		$location->lat = $position[0];
		$location->lng = $position[1];
		return $location;
	}
}