<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\CompanyService;
use App\Location;
use App\Branch;
use App\Person;
use Excel;

class CompaniesServiceController extends BaseController
{
    protected $company;
    protected $service;
    protected $location;
    protected $limit =2000;

    public function __construct(Company $company, CompanyService $service, Location $location)
    {
        $this->company = $company;
        $this->location = $location;
        $this->service = $service;
        $this->limit = config('app.location_limit');
    }
/*
	public function selectServiceDetails(Request $request) {
		// is this really neccessary? Surely it will only be model?
		if (is_object(request('id'))) {
			$id =request('id')->id;
		}else{
			$id = request('id');
		}
		
		if (! $company = $company->whereHas('serviceline',function($q) {
				$q->whereIn('servicelines.id',$this->userServiceLines);
			})->with('managedBy','industryVertical')
			->find($id)) {
			return redirect()->route('company.index');
		}
		dd($company->countlocations());
		$locations = $this->getCompanyLocations($company,request('state'));
		$states = $company->locations()->orderBy('state')->pluck('state')->unique()->toArray();
		if ($limited = $this->service->limitLocations($locations)) {
			$locations = $this->limitLocations($company);
		}
		$data = $this->service->getCompanyServiceBranchDetails($locations,$company);
		$data['segment'] = 'All';
		$data['statecode'] = $state;
		return response()->view('companies.service',compact('data','company','locations','limited','count','segment','states'));
	}

*/



    public function getServiceDetails($id, $state = null)
    {
        $company = $this->service->getCompany($id);
        $service = $this->service->getCompanyServiceBranchDetails($company->locations, $company, $limit = 5);

        return response()->view('companyservice.servicebranch', compact('company', 'service'));
    }
    public function getServiceTeamDetails($id, $state = null)
    {
        $company = $this->service->getCompany($id);
        $team = $this->service->getServiceTeam($id, $state);

        return response()->view('companyservice.serviceteam', compact('company', 'team'));
    }
    


/*
 * createServiceDetails Create multidimensional array from query result
 * @param  array  $locations result of getServiceDetails query
 * @param  integer $limit     Limit branch and reps returned if greater than limit
 * @return array           [description]
 */
    private function createServiceDetails($locations, $limit = 5)
    {

        $service = [];
        $loc = null;

        foreach ($locations as $location) {
            if (! isset($service[$location->id])) {
                $service[$location->id] = [];
            }
            $service[$location->id]['location']['id']= $location->id;
            $service[$location->id]['location']['businessname']= $location->businessname;
            $service[$location->id]['location']['street']= $location->locstreet;
            $service[$location->id]['location']['city']= $location->loccity;
            $service[$location->id]['location']['state']= $location->locstate;
            $service[$location->id]['location']['zip']= $location->loczip;
            if (! isset($service[$location->id]['branch']) || count($service[$location->id]['branch'])<$limit) {
                $service[$location->id]['branch'][$location->branch_id]['branch_id']=$location->branch_id;
                $service[$location->id]['branch'][$location->branch_id]['branchname']=$location->branchname;
                $service[$location->id]['branch'][$location->branch_id]['branchcity']=$location->city;
                $service[$location->id]['branch'][$location->branch_id]['branchstate']=$location->state;
                $service[$location->id]['branch'][$location->branch_id]['distance']=$location->branchdistance;
            }
            /*
			if (! isset($service[$location->id]['rep']) || count($service[$location->id]['rep'])<$limit) {
				$service[$location->id]['rep'][$location->pid]['pid']=$location->pid;
				$service[$location->id]['rep'][$location->pid]['repname']=$location->repname;
				$service[$location->id]['rep'][$location->pid]['distance']=$location->peepsdistance;
				//$service[$location->id]['rep'][$location->pid]['manager'][$location->depth] = $location->manager;	
			   }
			*/
        }
        return  $service;
    }



    public function newExportServicedetails($id)
    {
        // this can be refactored using ST_SPhere Distance
        $company =  $this->company
                    ->whereHas('serviceline', function ($q) {
                                $q->whereIn('serviceline_id', $this->userServiceLines);
                    })
                    ->findOrFail($id);
        $locations = $this->location->locationsNearbyBranches($company);
        $locations = $this->createServiceDetails($locations);
    }

    




    public function exportServiceDetails($id, $state = null)
    {

        $company =  $this->company
                    ->whereHas('serviceline', function ($q) {
                                $q->whereIn('serviceline_id', $this->userServiceLines);
                    })
                    ->findOrFail($id);
        $locations = $this->getCompanyLocations($company, $state);
        
        $limited = false;
        $count = count($locations);
        if ($count>$this->limit) {
            dd('limited');
            $companyname =  $this->chunkLocations($company, $locations);
            return redirect()->back()->with('success', 'File Created');
        } else {
            $title = $this->getTitle($company, $limited, $state, $loop = null);

            $this->writeExcel($title, $company, $locations);
            return redirect()->back();
        }
    }
    public function exportServiceTeamDetails($id, $state = null)
    {
        $company = $this->service->getCompany($id);
        $team = $this->service->getServiceTeam($id, $state = null);
        $title = $this->getTitle($company, $limited = null, $state, $loop = null, $team);
        $this->writeExcelTeam($title, $company, $team);
        return redirect()->back();
    }
    private function getTitle($company, $limited, $state, $loop, $team = null)
    {
        $title =$company->companyname;
        if ($state) {
            $title.=" ".strtoupper($state);
        }
        if ($team) {
            $title.=" service team";
        } else {
            $title.=" branch service locations";
        }
        if ($limited) {
            $title.=" (limited to ".$limited ." closest)";
        }
        if ($loop) {
            $title.=$loop;
        }
        return $title;
    }


    private function chunkLocations($company, $locations)
    {
        
        $title = $this->getTitle($company, $this->limit, $state = null, $loop = null);
        $companyname =strtolower(str_replace("'", "", str_replace(" ", "_", $company->companyname)));
        $servicelines = $company->serviceline->pluck('id')->toArray();
        $output = fopen(storage_path('app/public/exports/'.$companyname.".csv"), 'w');
        // output the column headings
        fputcsv($output, $this->getColumns());
        // fetch the data
        $allLocations = $locations->chunk(200);
        foreach ($allLocations as $locations) {
            fclose($output);
            $output = fopen(storage_path('app/public/exports/'.$companyname.".csv"), 'a');
            //$data = $this->service->getCompanyServiceBranchDetails($locations,$company,null);
            // loop over the locations, outputting them
    
            foreach ($locations as $location) {
                $data['salesteam'][$location->id]=$location->nearbySalesRep($servicelines)->get();
                $data['branches'][$location->id]=$location->nearbyBranches()->get();
                $row = $this->getContent($location, $data);

                fputcsv($output, $row);
            }
        }       fclose($output);
        return $companyname;
    }
    private function writeExcelTeam($title, $company, $team)
    {

        return  Excel::download($title, function ($excel) use ($company, $team) {
            
            $excel->sheet('Team', function ($sheet) use ($company, $team) {
                
                
                $sheet->loadview('companyservice.exportserviceteam', compact('company', 'team'));
            });
        })->download('csv');
    }
    private function writeExcel($title, $company, $locations)
    {
        
        return  Excel::download($title, function ($excel) use ($company, $locations) {
            $excel->sheet('Service', function ($sheet) use ($company, $locations) {
                
                $data = $this->service->getCompanyServiceBranchDetails($locations, $company, 5);
                
                $sheet->loadview('companyservice.exportbranchservicelocations', compact('data', 'locations'));
            });
        })->download('csv');
    }

    
    
    private function getCompanyServiceTeamDetails($locations, Company $company, $limit = 5)
    {
        $servicelines = $company->serviceline->pluck('id')->toArray();
    
        $data = [];
        
        foreach ($locations as $location) {
            $data['salesteam'][$location->id]=$location->nearbySalesRep($servicelines, $limit)->get();
        }

        return $data;
    }

    private function getCompanyLocations(Company $company, $state = null)
    {
        $locations = $this->location->where('company_id', '=', $company->id);


        if ($state) {
            $locations = $locations->where('state', '=', $state);
        }
        
        return $locations->get();
    }
    private function limitLocations(Company $company)
    {
                
        $location = $this->company->getMyPosition();
        $limited=$this->limit;
        return $this->location->nearby($location, '2000')
                ->where('company_id', '=', $company->id)
                ->limit($this->limit)
                ->get();
    }
}
