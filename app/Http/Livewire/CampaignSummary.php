<?php

namespace App\Http\Livewire;
use App\Campaign;
use App\Company;
use App\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignSummary extends Component
{
    use WithPagination;
    
    public $campaign;
    public $type = 'branch';
    
    public $perPage = 10;
    public $sortField = 'id';
    public $sortAsc = true;
    public $search ='';
    public $paginationTheme = 'bootstrap';


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

    public function mount($campaign_id)
    {
        
        $this->campaign = Campaign::with('companies', 'branches')->findOrFail($campaign_id);
    }

    public function render()
    {
        return view(
            'livewire.campaign-summary',
            ['data'=>$this->_getData()
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
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
                        $q->whereIn('company_id', $this->campaign->companies->pluck('id')->toArray())
                            ->where('address_branch.created_at', '<=', $this->campaign->dateto);
                        
                    }
                ]
            )->whereIn('id', $this->campaign->branches->pluck('id')->toArray());
            break;    
        }


    }
}
