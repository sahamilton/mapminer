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
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = '';
    public $status = 'All';
    public $paginationTheme = 'bootstrap';

    public $campaignid;
    public $view = 'leads';
    public $branchid;
    public $period;
    public $campaign;
    public $myBranches;
  

    public function updatingSearch()
    {
        $this->resetPage();
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
        
        $this->campaignid = Campaign::active()->first()->id;
        $myBranches = auth()->user()->person->getMyBranches();
        $this->myBranches = Branch::whereIn('id', $myBranches)->pluck('branchname', 'id');
        
        $this->branchid = reset($myBranches);

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
                'companies'=>Campaign::active()
                    ->find($this->campaignid)->companies->pluck('companyname', 'id')
                    ->toArray(),
                'campaigns'=>Campaign::active()->pluck('title', 'id')
                    ->toArray(),
                'branch' => Branch::findOrFail($this->branchid),
                'views'=>['leads','activities', 'opportunities'] ,
            ]
        );
    }

    private function _getData()
    {
        
        switch($this->view) {
        case 'leads':
            return Address::with('company')
                ->whereIn(
                    'company_id', 
                    Campaign::active()
                        ->find($this->campaignid)->companies->pluck('id')->toArray()
                )
                ->whereHas(
                    'assignedToBranch', function ($q) {
                        $q->where('branches.id', $this->branchid);
                    }
                );

            break;
            
        case 'activities':
            return Activity::with('relatesToAddress')
                ->where('branch_id', $this->branchid)
                ->whereBetween('activities.activity_date', [$this->period['from'], $this->period['to']])
                ->whereHas(
                    'relatesToAddress', function ($q) {
                        $q->whereIn('company_id', $this->campaign->companies->pluck('id')->toArray()); 
                    }         
                );

            break; 

        case 'opportunities':
            return Opportunity::where('branch_id', $this->branchid)
                ->where('opportunities.created_at', '<=', $this->campaign->dateto)
                ->where(
                    function ($q) { 
                        $q->whereClosed(0)
                            ->orWhereBetween('actual_close', [$this->period['from'], $this->period['to']]);
                    }
                )->whereHas(
                    'location', function ($q) {
                        $q->whereIn('company_id', $this->campaign->companies->pluck('id')->toArray()); 
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
