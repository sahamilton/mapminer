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
    use WithPagination;
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
    public $leadtype='lead';


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAccountype()
    {
        $this->$company_ids[0] = 'All';
    }

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
       
       
        
        $geocode = new Location;
        $this->location = $geocode->getMyPosition(); 
        $this->address = $this->location->address;  
        
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
                    ->when(
                        $this->accounttype != '0', function ($q) {
                            $q->whereHas(
                                'company', function ($q) {
                                    $q->where('accounttypes_id', $this->accounttype);
                                }
                            );
                        }
                    )
                    ->when(
                        $this->leadtype !='all', function($q) {
                            $q->when(
                                $this->leadtype==="lead", function ($q) {
                                    $q->doesntHave('assignedToBranch');
                                }
                            )->when(
                                $this->leadtype==="customer", function ($q) {
                                    $q->whereNotNull('isCustomer');
                                }
                            )->when(
                                $this->leadtype==="opportunity", function ($q) {
                                    $q->has('openOpportunities');
                                }
                            )->when(
                                $this->leadtype==="branchlead", function ($q) {
                                    $q->has('claimedByBranch');
                                }
                            )
                            ->when(
                                $this->leadtype==="offered", function ($q) {
                                    $q->has('offeredToBranch');
                                }
                            );
                        }
                    )    
                    ->nearby($this->location, $this->distance)
                    ->with('company', 'assignedToBranch')
                    ->withCount('contacts')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'accounttypes'=>$this->_getaccountTypes(),
                'companies'=>$this->_getCompanies(),
                'distances'=>[5=>5,10=>10,25=>25, 50=>50,100=>100],
                'leadtypes' =>['all'=>'All', 'opportunity'=>"Leads with active opportunities" , 'customer'=>'Customer leads', 'branchlead' => 'Branch leads', 'lead'=>'Unassigned leads','offered'=>"Offered Leads"],

            ]
        );
    }
    /**
     * [updateAddress description]
     * 
     * @return [type] [description]
     */
    public function updateAddress()
    {
        if ($this->address != $this->location->address) {
            $geocode = app('geocoder')->geocode($this->address)->get();
            
            $this->location->lat = $geocode->first()->getCoordinates()->getLatitude();
            $this->location->lng = $geocode->first()->getCoordinates()->getLongitude();
            $this->location->address = $this->address;
        }
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

    private function _getaccountTypes()
    {
        return AccountType::orderBy('type')->pluck('type', 'id')->toArray();
        
    }

    private function _getCompanies() :array
    {
        $companies=Company::when(
            $this->accounttype != '0', function ($q) {

                $q->where('companies.accounttypes_id', $this->accounttype);
            }
        )->whereHas(
            'locations', function ($q) {
                $q->nearby($this->location, $this->distance);
            }
        )
        ->orderBy('companyname')
        ->pluck('companyname', 'id')
        ->toArray();
        
        asort($companies);
        $all = ['all'=> 'All'];
        $companies = array_replace($all, $companies);
        
        return $companies;
    }
}
