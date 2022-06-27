<?php

namespace App\Http\Livewire;
use App\Location;
use Livewire\Component;
use App\Address;
use App\AccountType;
use App\Company;
use Livewire\WithPagination;
use App\Exports\ExportNearbyLocations;
use Excel;


    
class NearbyLocations extends Component
{
    use WithPagination, NearbyGeocoder;
    protected $paginationTheme = 'bootstrap';
    public Location $location;
    public $company_ids='all';
    public $address;
    public $distance = 25;
    public $sortField = 'distance';
    public $sortAsc ='true';
    public $perPage =10;
    public $accounttype = 0;
    public $search='';
    public array $myBranches;
    public $leadtype='lead';


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDistance()
    {
        $this->resetPage();
    }

    protected $rules = [
        'address' => 'required|min:8',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function mount()
    {
       
       
        $this->myBranches = auth()->user()->person->getMyBranches();
        $this->_geoCodeHomeAddress(); 

        
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->updateAddress();

        return view(
            'livewire.companies.nearby-locations', [
                'locations'=>Address::query()
                    ->search($this->search)
                   
                    ->when(
                        $this->company_ids != 'all', function ($q) {
                            $q->where('company_id', $this->company_ids);
                        }
                    )
                   /* ->when(
                        $this->accounttype != '0', function ($q) {
                            $q->whereHas(
                                'company', function ($q) {
                                    $q->where('accounttypes_id', $this->accounttype);
                                }
                            );
                        }
                    )*/
                    ->doesntHave('assignedToBranch')
                    /*->when(
                        $this->leadtype !='all', function($q) {
                            $q->when(
                                $this->leadtype === "lead", function ($q) {
                                    $q->doesntHave('assignedToBranch');
                                }
                            )->when(
                                $this->leadtype === "customer", function ($q) {
                                    $q->whereNotNull('isCustomer')
                                      ->whereHas('claimedByBranch', function ($q) {
                                            $q->whereIn('branches.id', $this->myBranches);
                                        }
                                    );
                                }
                            )->when(
                                $this->leadtype === "opportunity", function ($q) {
                                    $q->has('openOpportunities')
                                    ->whereHas('claimedByBranch', function ($q) {
                                            $q->whereIn('branches.id', $this->myBranches);
                                        }
                                    );
                                }
                            )->when(
                                $this->leadtype==="branchlead", function ($q) {
                                    $q->whereHas('claimedByBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->myBranches);
                                    });
                                }
                            )->when(
                                $this->leadtype === "otherbranchlead", function ($q) {
                                    $q->whereHas('claimedByBranch', function ($q) {
                                        $q->whereNotIn('branches.id', $this->myBranches);
                                    });
                                }
                            )
                            ->when(
                                $this->leadtype === "offered", function ($q) {
                                    $q->whereHas('offeredToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->myBranches);
                                    });
                                }
                            );
                        }
                    ) */   
                    ->nearby($this->location, $this->distance)
                    ->with('company', 'assignedToBranch', 'leadsource')
                    ->withCount('contacts')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                
                'companies'=>$this->_getCompanies(),
                'distances'=>[1=>1,5=>5,10=>10,25=>25],
                

            ]
        );
    }

    public function export()
    {
        $this->updateAddress();
       
        return Excel::download(
            new ExportNearbyLocations(
                $this->location, 
                $this->distance,
                $this->accounttype,
                $this->company_ids
            ), 'nearbylocations.csv'
        );
    }
    
    

    private function _getCompanies() :array
    {
        
      
        $companies=Company::has('unassigned')
        ->whereHas(
            'unassigned', function ($q) {
                $q->nearby($this->location, $this->distance);
            }
        )

        ->orderBy('companyname')
        ->pluck('companyname', 'id')
        ->toArray();
        
        asort($companies);
        $all = ['all'=> 'All Companies'];
        $companies = array_replace($all, $companies);
        
        return $companies;
    }
}
