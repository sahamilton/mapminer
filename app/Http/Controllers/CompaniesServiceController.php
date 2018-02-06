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
    protected $limit =100;

	public function __construct (Company $company,Location $location){
		$this->company = $company;
		$this->location = $location;
		$this->limit = config('app.location_limit');
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
		$states = $company->locations()->orderBy('state')->pluck('state')->unique()->toArray();
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
			return $this->chunkLocations($company,$locations);
			
		}else{


		$title = $this->getTitle($company,$limited,$state,$loop=null);

		$this->writeExcel($title,$company,$locations);
		return redirect()->back();
		}

	}
	
	private function getTitle($company,$limited,$state,$loop){
		$title =$company->companyname;
		if($state){
			$title.=" ".strtoupper($state);
		}
		$title.=" service locations";
		if($limited){
			$title.=" (limited to ".$limited ." closest)";
		}
		if($loop){
			$title.=$loop;
		}
		return $title;
	}


	private function chunkLocations($company,$locations){
		
		$title = $this->getTitle($company,$this->limit,$state=null,$loop=null); 
		$companyname =strtolower(str_replace("'","",str_replace(" ", "_", $company->companyname)));
		$servicelines = $company->serviceline->pluck('id')->toArray();
 		$output = fopen(storage_path('app/public/exports/'.$companyname.".csv"), 'w');
		// output the column headings
		fputcsv($output, $this->getColumns());
		// fetch the data
		$allLocations = $locations->chunk(400);
		 foreach($allLocations as $locations){
			//$data = $this->getCompanyServiceDetails($locations,$company,null);
			// loop over the locations, outputting them
	
			foreach ($locations  as $location){
				$data['salesteam'][$location->id]=$location->nearbySalesRep($servicelines)->get();
				$data['branches'][$location->id]=$location->nearbyBranches()->get();
				$row = $this->getContent($location,$data);

				fputcsv($output, $row);
			} 
			exit;
		}
		fclose($output);
		return redirect()->back();
		
	}


	private function getColumns(){
		 return['Business Name',
				'Street',
				'City',
				'State',
				'ZIP',
				'Branch 1',
				'Branch 1 Address',
				'Branch 1 Proximity (miles)',
				'Branch 2',
				'Branch 2 Address',
				'Branch 2 Proximity (miles)',
				'Branch 3',
				'Branch 3 Address',
				'Branch 3 Proximity (miles)',
				'Branch 4',
				'Branch 4 Address',
				'Branch 4 Proximity (miles)',
				'Branch 5',
				'Branch 5 Address',
				'Branch 5 Proximity (miles)',
				'Reps 1',
				'Reps 1 Phone',
				'Reps 1 Email',
				'Reps 2',
				'Reps 2 Phone',
				'Reps 2 Email',
				'Reps 3',
				'Reps 3 Phone',
				'Reps 3 Email',
				'Reps 4',
				'Reps 4 Phone',
				'Reps 4 Email',		
				'Reps 5',
				'Reps 5 Phone',
				'Reps 5 Email',
				'Manager'];
   		

	}

	private function getContent($location,$data){
		$limit =5;
		$content = array();
   
			$content[]=	$location->businessname;
			$content[]=	$location->street;
			$content[]=	$location->city;
			$content[]=	$location->state;
			$content[]=	$location->zip;
			
			$branchcount =null;
				if(isset($data['branches'][$location->id])){
					foreach($data['branches'][$location->id] as $branch){
					 $branchcount++;
						$content[]="Branch". $branch->id;
						$content[]=trim($branch->street)." ". trim($branch->address2)." ". trim($branch->city)." ".  trim($branch->state)." ".  trim($branch->zip);
						$content[]=number_format($branch->distance,0);
					
					}
				}
				for($i=0;$i<$limit-$branchcount;$i++){
					$content[]=" ";
					$content[]=" ";
					$content[]=" ";
						
				}
				$teamcount =null;
				if(isset($data['salesteam'][$location->id])){
						foreach($data['salesteam'][$location->id] as $team){
						$teamcount++;
							$content[]=$team->postName() ." : ". number_format($team->distance,1)." miles";
							$content[]=$team->phone;
							$content[]=$team->userdetails->email;
						}
				}
				for($i=0;$i<$limit-$teamcount;$i++){
						$content[]=" ";
						$content[]=" ";
						$content[]=" ";
						}
			$manager=null;
			if(count($data['salesteam'][$location->id])>0){
					
					foreach($data['salesteam'][$location->id][0]->getAncestors()->reverse() as $managers){
						if($managers->reports_to){
							$manager.=$managers->postName().",";
						}
					
					}
					$manager = rtrim($manager,',');
			}
			$content[]=$manager;
			return $content;

	}
	private function writeExcel($title,$company,$locations){
		return 	Excel::create($title,function($excel) use($company,$locations){
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
	
		return $this->location->nearby($location,'2000')
		->where('company_id','=',$company->id)
		->limit($this->limit)
		->get();
	}
}
