<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Location;
use Excel;
class CompaniesServiceController extends BaseController
{
    protected $company;
    protected $location;
    protected $limit =500;

	public function __construct (Company $company,Location $location){
		$this->company = $company;
		$this->location = $location;
		$this->limit = config('app.location_limit');
		parent::__construct($this->company);
	}
  /**
   * [selectServiceDetails description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
	public function selectServiceDetails(Request $request){

		return $this->serviceDetails($request->get('id'),$request->get('state'));
	}
/**
 * [getServiceDetails description]
 * @param  [type] $id    [description]
 * @param  [type] $state [description]
 * @return [type]        [description]
 */
	public function getServiceDetails($id){
		$company = $this->company->with('managedBy','locations')->findOrFail($id);
		$count = count($company->locations);
		if($count > $this->limit){
			dd($count,$this->location->getStateSummary($company->id));
      // sho view with states and number;
      // select states limited to number_format
      //
		}
		$locations = $this->location->locationsNearbyBranches($company);
		$locations = $this->createServiceDetails($locations);

		return response()->view('companies.newservice',compact('company','locations'));

	}
/**
 * [createServiceDetails Create multidimensional array from query result]
 * @param  array  $locations [result of getServiceDetails query]
 * @param  integer $limit     Limit branch and reps returned if greater than limit
 * @return array           [description]
 */
  private function createServiceDetails($locations,$limit=5){
		$service = array();
		$loc = null;

		foreach ($locations as $location){

			if(! isset($service[$location->id])){
				$service[$location->id] = array();
			}
			$service[$location->id]['location']['id']= $location->id;
			$service[$location->id]['location']['businessname']= $location->businessname;
			$service[$location->id]['location']['street']= $location->locstreet;
			$service[$location->id]['location']['city']= $location->loccity;
			$service[$location->id]['location']['state']= $location->locstate;
			$service[$location->id]['location']['zip']= $location->loczip;
			if(! isset($service[$location->id]['branch']) || count($service[$location->id]['branch'])<$limit){
				$service[$location->id]['branch'][$location->branch_id]['branch_id']=$location->branch_id;
				$service[$location->id]['branch'][$location->branch_id]['branchname']=$location->branchname;
				$service[$location->id]['branch'][$location->branch_id]['address']=$location->city . " " .$location->state ;
				$service[$location->id]['branch'][$location->branch_id]['phone']=$location->branch_phone;
				$service[$location->id]['branch'][$location->branch_id]['distance']=$location->branchdistance;
				}

			if(! isset($service[$location->id]['rep']) || count($service[$location->id]['rep'])<$limit){
				$service[$location->id]['rep'][$location->pid]['pid']=$location->pid;
				$service[$location->id]['rep'][$location->pid]['repname']=$location->repname;
				$service[$location->id]['rep'][$location->pid]['phone']=$location->phone;
				$service[$location->id]['rep'][$location->pid]['distance']=$location->peepsdistance;
				//$service[$location->id]['rep'][$location->pid]['manager'][$location->depth] = $location->manager;
			   }

		}
		return  $service;
	}


/**
 * [exportServiceDetails description]
 * @param  [type] $id    [description]
 * @param  [type] $state [description]
 * @return CSV download file
 */
	public function exportServiceDetails($id,$state=null){
    	$company = 	$this->company
					->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);
							})
					->findOrFail($id);
    $title = $this->getTitle($company);
		return 	Excel::create($title,function($excel) use($company,$title){
			$excel->sheet($title,function($sheet) use($company) {
				$locations = $this->location->locationsNearbyBranches($company);
				$locations = $this->createServiceDetails($locations);
				$sheet->loadview('companies.exportnewservicelocations',compact('locations'));
			});
		})->download('csv');

	}
	/**
   * [getTitle description]
   * @param  [type] $company [description]
   * @param  [type] $limited [description]
   * @param  [type] $state   [description]
   * @param  [type] $loop    [description]
   * @return [type]          [description]
   */
	private function getTitle($company){
		 return
     str_replace(" ","_",
     mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '',
     substr($company->companyname,0,10)) ." service locations");
	}
}
