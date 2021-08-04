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

    public Campaign $campaign;
    public $type = 'company';

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
                'campaign_leads',
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
    public function mount($campaign_id)
    {
        
        $this->campaign = Campaign::with('companies', 'branches')->findOrFail($campaign_id);
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
        return view(
            'livewire.campaign-tracking',
            ['data'=>$this->_getData()
                ->search($this->search)
                ->orderBy($sort, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                //'summarycount'=>$this->_summaryCounts(),
                //'assignable'=>$this->_assignable(),
            ]
        );
    }

    private function _getData()
    {

        switch($this->type) {
        case 'company':
            return Company::whereIn('id', $this->campaign->companies->pluck('id')->toArray())
                ->companyCampaignSummaryStats($this->campaign, $this->fields);

            break;    

        case 'branch':
            return Branch::hasCampaignleads($this->campaign)
                ->summaryOpenCampaignStats($this->campaign, $this->fields)
                ->whereIn('id', $this->campaign->branches->pluck('id')->toArray());
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
        $data['assigned'] = $all->sum('assigned_count');
        if ($this->type == 'company') {
            $data['unassigned'] = $all->sum('unassigned_count');
            
        }
        return $data;

    }
}
