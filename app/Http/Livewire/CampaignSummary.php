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
    public Campaign $campaign;
    public $campaign_id;
    public $company_ids;
    public $vertical_ids;
    public $branch_ids;
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

    public function mount(Campaign $campaign)
    {
        
        $this->campaign = $campaign;
        $this->campaign_id = $this->campaign->id;
        $this->branch_ids = $this->campaign->branches->pluck('id')->toArray();
        $this->company_ids =$this->campaign->companies->pluck('id')->toArray();
        $this->vertical_ids = $this->campaign->vertical->pluck('id')->toArray();
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
               
            ]
        );
    }

    private function _getData()
    {

        switch($this->type) {
        case 'company':
            return Company::withCount('assigned', 'unassigned')
                ->whereIn('id', $this->company_ids);
            break;    


        case 'branch':
            return Branch::withCount(       
                [ 
                    'addresses as assigned_count'=>function ($q) {
                        $q->where(
                            function ($q) {
                                $q->whereIn('company_id', $this->company_ids)
                                    ->orWhereIn('industry_id', $this->vertical_ids);
                            }
                        )->where('address_branch.created_at', '<=', $this->campaign->dateto);
                    }
                ]
            )->whereIn('id', $this->branch_ids);
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
        $branches = Branch::whereIn('id', $this->branch_ids)->get();
        
        foreach ($branches as $branch) {
              $assignable[$branch->id] = Address::nearby($branch, 15)
                    ->doesntHave('assignedToBranch')
                    ->where(
                        function ($q) {
                            $q->whereIn('industry_id', $this->vertical_ids)
                                ->orWhere('company_id', $this->company_ids);
                        }
                    )->count();
        }
        return $assignable;
       
    }
    private function _getCurrentCampaign()
    {
        

        $this->branches = array_intersect(auth()->user()->person->getMyBranches(), $this->branch_ids);
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
