<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Company;
use App\Address;
use App\AccountType;
use Livewire\WithPagination;
use Excel;
use App\Exports\ExportCompanyLocationCount;

class AccountLocation extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'companyname';
    public $accounttype='1';
    public $sortAsc = true;
    public $search ='';
    public $distance = '25';
    public $address;
    public $latlng;
    public $type;
    public $location;




    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    /**
     * [sortBy description]
     * 
     * @param STR $field [description]
     * 
     * @return [type]        [description]
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    
    /**
     * [updateAddress description]
     * 
     * @return [type] [description]
     */
    public function updateAddress()
    {
        if ($this->address) {
            $geocode = app('geocoder')->geocode($this->address)->get();
           
            $coordinates = $geocode->first()->getCoordinates();
            $this->latlng['lat'] = $coordinates->getLatitude();
            $this->latlng['lng'] = $coordinates->getLongitude();

        } else {
            $this->latlng =null;
        }
        

    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        
        return view(
            'livewire.account-location',
            [
                'companies'=>Company::query()
                    ->search($this->search)
                    ->has('locations')
                    ->withLastUpdatedId()
                    ->when(
                        $this->latlng, function ($q) {
                            $q->withCount(
                                [
                                    'locations'=>function ($q) {
                                        $q->countNearby($this->latlng, $this->distance);
                                    }
                                ]
                            )->withCount(
                                [
                                    'locations as assigned'=>function ($q) { 
                                        $q->countNearby($this->latlng, $this->distance)
                                            ->has('assignedToBranch');
                                    }
                                ]
                            );
                        }, function ($q) {
                            return $q->withCount('locations')
                                ->withCount(
                                    [
                                        'locations as assigned'=>function ($q) { 
                                            $q->has('assignedToBranch');
                                        }
                                    ]
                                );
                        }
                    )
                   
                    
                    ->with('managedBy', 'lastUpdated')
                    ->when(
                        $this->accounttype != '0', function ($q) {

                            $q->where('accounttypes_id', $this->accounttype);
                        }
                    )
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'accounttypes' => AccountType::orderBy('type')->pluck('type', 'id')->prepend('All', '0')->toArray(),
                'distances'=>['10','20','50','100'],
                
            ]
        );
    }
    /**
     * [export description]
     * 
     * @return Excel [description]
     */
    public function export()
    {
        
        return Excel::download(
            new ExportCompanyLocationCount(
                $this->accounttype, 
                $this->latlng, 
                $this->distance, 
                $this->address
            ), 'companylocationcount.csv'
        );
    }
    /**
     * [_makeLocation description]
     * 
     * @return [type] [description]
     */
    private function _makeLocation()
    {
        $this->location = new Address;
        $this->location->lat = $this->lat;
        $this->location->lng = $this->lng;
    }
  



}