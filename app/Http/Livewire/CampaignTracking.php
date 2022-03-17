<?php

namespace App\Http\Livewire;
use App\Campaign;
use App\Company;
use App\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignTracking extends Component
{
    use WithPagination;
    public $branches;
   
    public Campaign $campaign;
    public $type = 'company';
    public $campaigns;
    public $campaign_id; 
    public $perPage = 10;
    public $sortField = 'name';
    public $sortAsc = true;
    public $search = '';
    public $paginationTheme = 'bootstrap';
    public $fields;
    public $branchfields = [  
                'campaign_leads',
                'touched_leads',
                'new_opportunities',
                'open_opportunities',
                'won_opportunities',
                'won_value'
            ];
    public $companyfields = [
                'unassigned_leads', 
                'assigned_leads',
                'touched_leads',
                'new_opportunities',
                'open_opportunities',
                'won_opportunities',
                'won_value'
            ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function mount()
    {
        
        $this->campaigns = Campaign::active()->get();
        $this->campaign = $this->campaigns->first();
        $this->campaign_id = $this->campaign->id;
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
    public function render()
    {
        $sort = $this->_setSort();
        $this->fields = $this->_getTypeFields();
        $this->_getCurrentCampaign();

        return view(
            'livewire.campaign-tracking',
            ['data'=>$this->_getData()
                ->search($this->search)
                ->orderBy($sort, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'summarycount'=>$this->_summaryCounts(),
                
            ]
        );
    }

    private function _getData()
    {

        switch($this->type) {
        case 'company':
            return Company::whereIn('id', $this->campaign->companies->pluck('id')->toArray())
                ->whereHas(
                    'locations', function ($q) {
                        $q->whereHas(
                            'assignedToBranch', function ($q) {
                                $q->whereIn('branches.id', $this->branches);
                            }  
                        );
                    }
                )
                ->companyCampaignSummaryStats($this->campaign, $this->fields);

            break;    

        case 'branch':
            return Branch::hasCampaignleads($this->campaign)
                ->summaryOpenCampaignStats($this->campaign, $this->fields)
                ->whereIn('id', $this->branches);
            break;    
        }


    }
    private function _getTypeFields()
    {
        switch($this->type) {
        case "company":
            return $this->companyfields;
            break;
        case "branch":
            return $this->branchfields;
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

    private function _summaryCounts()
    {
        $all = $this->_getData()->get();
        foreach ($this->fields as $field){
            $data[$field] = $all->sum($field);
        }

        return $data;

    }
    private function _getCurrentCampaign()
    {
        
        $this->campaign = Campaign::with('campaignmanager', 'branches')->findOrFail($this->campaign_id);

        if (auth()->user()->person->id === $this->campaign->campaignmanager->id) {
            $this->branches = $this->campaign->branches->pluck('id')->toArray();
           
        } else {
            $this->branches = array_intersect(auth()->user()->person->getMyBranches(), $this->campaign->branches->pluck('id')->toArray()); 
        }

        
    }
}
