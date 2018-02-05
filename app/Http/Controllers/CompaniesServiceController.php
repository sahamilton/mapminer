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
    protected $limit = 500;

	public function __construct (Company $company,Location $location){
		$this->company = $company;
		$this->location = $location;
		parent::__construct($this->company);
	}
	public function selectServiceDetails(Request $request){
		

		return $this->serviceDetails($request->get('id'),$request->get('state'));
	}

    public function serviceDetails($id,$state=null){
		if(is_object($id)){
			$id = $id->id;
		}
		
		if (! $company = $this->company->checkCompanyServiceLine($id,$this->userServiceLines))
		{
			return redirect()->route('company.index');
		}	
		$company = $company->with('managedBy','industryVertical')
		->find($id);
		if(! $company){
				return redirect()->route('company.index');
		}
		$locations = $this->getCompanyLocations($company,$state);
		$states = $company->locations()->pluck('state')->unique()->toArray();
		$limited = false;
		$count = count($locations);
		if($count>$this->limit){
			$locations = $this->limitLocations($company);
			$limited = $this->limit;
		}
		

		$data = $this->getCompanyServiceDetails($locations,$company);
		$data['segment'] = 'All';
		$data['statecode'] = $state;
		return response()->view('companies.service',compact('data','company','locations','limited','count','segment','states'));
	}

	public function exportServiceDetails($id,$state=null){

		$company = 	$this->company
					->whereHas('serviceline', function($q){
							    $q->whereIn('serviceline_id', $this->userServiceLines);

							})					
					->findOrFail($id);
		$locations = $this->getCompanyLocations($company,$state);
		$limited = false;
		$count = count($locations);
		if($count>$this->limit){
			$locations = $this->limitLocations($company);
			$limited = $this->limit;
		}

		$title =$company->companyname;
		if($state){
			$title.=" ".strtoupper($state);
		}
		$title.=" service locations";
		if($limited){
			$title.=" (limited to ".$limited ." closest)";
		}
		Excel::create($title,function($excel) use($company,$locations){
			$excel->sheet('Service',function($sheet) use($company,$locations) {
				
				$data = $this->getCompanyServiceDetails($locations,$company,null);
				$sheet->loadview('companies.exportservicelocations',compact('data','locations'));
			});
		})->download('csv');

	}

	private function getCompanyServiceDetails($locations,Company $company){
		$servicelines = $company->serviceline->pluck('id')->toArray();
	
		$data = array();
		foreach ($locations as $location){
			$data['salesteam'][$location->id]=$location->nearbySalesRep($servicelines)->get();
			$data['branches'][$location->id]=$location->nearbyBranches()->get();

		}
		return $data;
	}

	private function getCompanyLocations(Company $company, $state=null){
		$locations = $this->location->where('company_id','=',$company->id);

		if($state){
			$locations = $locations->where('state','=',$state);
		}	

		return $locations->get();
	}
	private function limitLocations(Company $company){
				
			$location = new \stdClass;
			$limited=$this->limit;
			if (\Session::has('geo'))
				{
					$geo = \Session::get('geo');
					$location->lat = $geo['lat'];
					$location->lng = $geo['lng'];
				}else{
					// use center of the country as default lat lng
					$location->lat =  '47.25';
					$location->lng =  '-122.44';

				}
	
		return $this->location->nearby($location,'1000')
		->where('company_id','=',$company->id)
		->limit($this->limit)
		->get();
	}
}
