<?php

namespace App\Http\Livewire;

use App\Campaign;
use App\Branch;
use App\Address;
use App\Activity;
use App\Opportunity;
use Livewire\Component;
use Livewire\WithPagination;
class BranchCampaign extends Component
{
    use WithPagination;
   
    public $perPage = 10;
    public $sortField = 'id';
    public $sortAsc = true;
    public $search = '';
    public $status = 'All';
    public $paginationTheme = 'bootstrap';
    public $company_id = 'All';
    public $campaignid;
    public $view = 'leads';
    public $branch_id;
    public $myBranches;
    public $period;
    public $campaign;


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingBranchId()
    {
        $this->resetPage();
    }


    public function updatingView()
    {
        $this->resetPage();
        switch($this->view) {
        case 'leads':
            $this->sortField='businessname';
            break;
        case 'activities':
            $this->sortField = 'activity_date';
            break;  
        case 'opportunities':
            $this->sortField = 'expected_close';
            break;
        }
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

    public function mount($campaign_id, $branch_id=null)
    {
        
        $this->campaignid = $campaign_id;
        $myBranches = auth()->user()->person->getMyBranches();
       
        $this->myBranches = Branch::whereIn('id', $myBranches)->get();
       
        if (! $branch_id) {            
            $branch_id = $this->myBranches->first()->id;
        }
        $this->branch_id  =$branch_id;
      
    }

    public function render()
    {
       
        $this->_setCampaignPeriod(); 
        //$this->_test();
        return view(
            'livewire.branch-campaign',
            [
                'data'=>$this->_getData()
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'companies'=>Campaign::find($this->campaignid)->companies->sortBy('companyname')->pluck('companyname', 'id')
                    ->toArray(),
                'campaigns'=>Campaign::active()->pluck('title', 'id')
                    ->toArray(),
                'branch' => Branch::findOrFail($this->branch_id),
                'views'=>['leads','activities', 'opportunities'] ,
                
            ]
        );
    }

    private function _getData()
    {
       
        switch($this->view) {
        case 'leads':

            $this->sortField='businessname';
            
            return Address::withLastActivityId()
                ->with('company', 'lastActivity')
                ->whereIn(
                    'company_id', 
                    Campaign::active()
                        ->find($this->campaignid)->companies->pluck('id')->toArray()
                )
                ->when(
                    $this->company_id != 'All', function ($q) {
                        $q->where('company_id', $this->company_id);
                    }
                )
                ->whereHas(
                    'assignedToBranch', function ($q) {
                        $q->where('branches.id', $this->branch_id);
                    }
                );

            break;
            
        case 'activities':

            $this->sortField = 'activity_date';
           
            return Activity::with('relatesToAddress')
                ->where('branch_id', $this->branch_id)
                ->whereBetween('activities.activity_date', [$this->period['from'], $this->period['to']])
                ->whereHas(
                    'relatesToAddress', function ($q) {
                        $q->when(
                            $this->company_id != 'All', function ($q) {
                                $q->where('company_id', $this->company_id);
                            }, function ($q) {
                                $q->whereIn('company_id', $this->campaign->companies->pluck('id')->toArray()); 
                            } 
                        );
                    }         
                );

            break; 

        case 'opportunities':

            $this->sortField = 'expected_close';
           
            return Opportunity::where('branch_id', $this->branch_id)
                ->where('opportunities.created_at', '<=', $this->campaign->dateto)
                ->where(
                    function ($q) { 
                        $q->whereClosed(0)
                            ->orWhereBetween('actual_close', [$this->period['from'], $this->period['to']]);
                    }
                )->whereHas(
                    'location', function ($q) {
                        $q->whereHas(
                            'campaigns', function ($q) {
                                $q->where('campaigns.id', $this->campaign->id);
                            }
                        );
                    }     
                );
            break;
        }
    }

    private function _setCampaignPeriod()
    {
        $this->campaign = Campaign::findOrFail($this->campaignid);

        $this->period = ['from'=>$this->campaign->datefrom, 'to'=>$this->campaign->dateto];
    }

    private function _test()
    {
        dd($this->view, $this->_getData()->count());
    }
}
