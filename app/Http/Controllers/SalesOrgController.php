<?php
namespace App\Http\Controllers;

use App\Branch;
use App\Person;
use App\Address;
use Excel;
use App\Http\Requests\LeadAddressFormRequest;
use Illuminate\Http\Request;

class SalesOrgController extends BaseController
{
    public $distance = 20;
    public $limit = 5;
    public $branch;
    public $person;
    public $salesroles = [4,5,6,7,8];
    


    public function __construct(Branch $branch, Person $person, Address $address)
    {
        $this->person = $person;
        $this->branch = $branch;
        $this->address = $address;

        //$this->person->rebuild();
    }
    
    public function index()
    {

        $salesperson = $this->loadSalesOrgRelations($this->getSalesLeaders());
        return response()->view('salesorg.salesmanagerlist', compact('salesperson'));
    }


    public function show(Request $request, Person $salesperson)
    {
        
        
        if ($salesperson->isLeaf()) {
                $salesorg = $this->loadSalesOrgRelations($salesperson);

                return response()->view('salesorg.map', compact('salesorg'));
        } else {
            $salesteam = $this->loadSalesOrgRelations($salesperson);
            if (request()->has('view') && request('view')=='list') {
                return response()->view('salesorg.salesmanagerlist', compact('salesperson'));
            }
            return response()->view('salesorg.managermap', compact('salesteam'));
        }
    }
    public function getSalesOrgList($salesperson)
    {
        // this could be combined with getSAlesBranches and
        // refactored to function show
            
            $salesperson->load('userdetails.roles', 'directReports', 'directReports.userdetails', 'directReports.userdetails.roles', 'reportsTo.userdetails.roles');
            
            
            return response()->view('salesorg.salesmanagerlist', compact('salesperson'));
    }

    /*
	
	 */
    public function getSalesBranches($salesPerson = null)
    {
                        
        // if not id then find root salesorg id
            
        if (! $salesPerson) {
            $salesperson = $this->getSalesLeaders();
        } else {
            $salesperson = Person::whereId($salesPerson->id)->first();
        }
            
        // if leaf
            
            
        if ($salesperson->isLeaf()) {
            $salesorg = $salesperson->load('userdetails.roles', 'reportsTo', 'reportsTo.userdetails.roles', 'branchesServiced');

            return response()->view('salesorg.map', compact('salesorg'));
        } else {
            $salesteam = $this->loadSalesOrgRelations($salesperson);
                
            return response()->view('salesorg.managermap', compact('salesteam'));
        }
    }

    public function salesCoverageMap()
    {
        $this->salesCoverageData();
        return response()->make('salesorg.coveragemap');
    }

    

    /*
	
	 */
    private function getSalesOrg()
    {
        $salesorg = $this->person->with('userdetails', 'userdetails.roles', 'userdetails.serviceline')
        ->whereHas('userdetails.roles', function ($q) {
            $q->where('name', '=', 'sales');
        })
        ->whereNotNull('lat')
        ->get();
        return $salesorg;
    }
    /*
	
	 */
    private function getServicelines($servicelines)
    {
        $userServiceLines = array();
        foreach ($servicelines as $serviceline) {
            $userServiceLines[]= $serviceline->id;
        }
        return $userServiceLines;
    }

    private function getLocalBranches($salesrep)
    {
        
        $userServiceLines = $this->getServicelines($salesrep->userdetails->serviceline);

        $branches = $this->branch->whereHas('servicelines', function ($q) use ($userServiceLines) {
            $q->whereIn('serviceline.id', $userServiceLines);
        })
        ->nearby($salesrep, $this->distance, $this->limit)
        
        ->get();
        $branchIds = array();
        foreach ($branches as $branch) {
            $branchIds[] = $branch['branchid'];
        }
        return $branchIds;
    }


    
    private function getSalesLeaders()
    {
        
        //refactor to remove hard coding
        //
        //// Head of sales organization
        return $this->person->getPersonsWithRole([14])->first();

        /*return (Person::where('depth','=',0)
			->whereNull('reports_to')
			->whereRaw('lft+1 != rgt')
			_.whereHas('role == sales')
			->pluck('id'));
		return $person = ['1767'];*/
    }


    public function noManager()
    {
        $people = $this->person->whereNull('reports_to')
        ->with('userdetails', 'userdetails.roles')
        ->get();
        $title="Users with no manager";


        return response()->view('admin.users.nomanager', compact('people', 'title'));
    }

    public function noManagerExport()
    {
        

        Excel::download('NoManagers'.time(), function ($excel) {
            $excel->sheet('No Managers', function ($sheet) {
                $people = $this->person->whereNull('reports_to')
                ->with('userdetails', 'userdetails.roles')
                ->get();
                $sheet->loadView('admin.users.nmexport', compact('people'));
            });
        })->download('csv');
    }


    private function loadSalesOrgRelations(Person $salesperson)
    {
        return $salesperson->load('userdetails.roles', 'directReports', 'directReports.userdetails', 'directReports.userdetails.roles', 'reportsTo.userdetails.roles');
    }

    public function find(LeadAddressFormRequest $request)
    {
    

        $geoCode = app('geocoder')->geocode(request('address'))->get();

        if (! $geoCode or count($geoCode)==0) {
            return redirect()->back()->withInput()->with('error', 'Unable to Geocode address:'.request('address'));
        } else {
            request()->merge($this->person->getGeoCode($geoCode));
        }
        $data = request()->all();

        if (! request()->has('number')) {
            $data['number']=5;
        }

        session()->put('geo', $data);
        $address = new Address($data);
      
        if ($request->type =='branch') {
            $branches = $this->branch->nearby($address, $data['distance'], $data['number'])->get();
            return response()->view('salesorg.nearbybranches', compact('data', 'branches'));
        } else {
            $people= $this->person->nearby($address, $data['distance'], $data['number'])->get();
            return response()->view('salesorg.nearbypeople', compact('data', 'people'));
        }
    }
}
