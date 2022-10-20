<?php

namespace App\Http\Livewire;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\Address;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignSummary extends Component
{
    use WithPagination;
    
    public $campaigns;
    public $campaign;
    public $campaign_id;
    public $type = 'company';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortAsc = true;
    public $search ='';
    public $paginationTheme = 'bootstrap';


    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingType()
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

    public function mount($campaign_id=null)
    {
        
        $this->campaigns = Campaign::where('status', 'planned')->orderBy('id', 'desc')->get();
        if ($campaign_id) {
             $this->campaign = Campaign::find($campaign_id);
        } else {
            $this->campaign = $this->campaigns->first();
        }
        //$this->_getCampaignType();
        $this->campaign_id = $this->campaign->id;

    }

    public function render()
    {
        $sort = $this->_setSort();
        $this->_getCurrentCampaign();
       
        return view(
            'livewire.campaign-summary',
            ['data'=>$this->_getData()
                ->search($this->search)
                ->orderBy($sort, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'summarycount'=>$this->_summaryCounts(),
                'assignable'=>$this->_assignable(),
                'campaign' => Campaign::findOrFail($this->campaign_id),
            ]
        );
    }

    private function _getData()
    {

        switch($this->type) {
        case 'company':
            return Company::withCount('assigned', 'unassigned')
                ->whereIn('id', $this->campaign->companies->pluck('id')->toArray());
            break;    


        case 'branch':
            return Branch::withCount(       
                [ 
                    'addresses as assigned_count'=>function ($q) {
                        $q->where(
                            function ($q) {
                                $q->whereIn('company_id', $this->campaign->companies->pluck('id')->toArray())
                                    ->orWhereIn('industry_id', $this->campaign->vertical->pluck('id')->toArray());
                            }
                        )->where('address_branch.created_at', '<=', $this->campaign->dateto);
                    }
                ]
            )->whereIn('id', $this->campaign->branches->pluck('id')->toArray());
            break;    
        }


    }

    private function _setSort()
    {
        if ($this->sortField == 'name') {
            return $this->type.'name';
        } else {
            return $this->sortField;
        }
    }

    private function _assignable()
    {
        
        if ($this->type == 'branch') {
            $addresses = Address::doesntHave('assignedToBranch')
                ->whereIn('company_id', $this->campaign->companies->pluck('id')->toArray())
                ->pluck('id')->toArray();
            return collect($this->campaign->getAssignableLocationsofCampaign($addresses, true));
        }
        return [];
       
    }
    private function _getCurrentCampaign()
    {
        
        $this->campaign = Campaign::findOrFail($this->campaign_id);

        $this->branches = array_intersect(auth()->user()->person->getMyBranches(), $this->campaign->branches->pluck('id')->toArray());
    }
    private function _summaryCounts()
    {
        $all = $this->_getData()->get();
        $data['assigned'] = $all->sum('assigned_count');
        if ($this->type == 'company') {
            $data['unassigned'] = $all->sum('unassigned_count');
            
        }
        return $data;

    }
}
