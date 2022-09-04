<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LeadSource;
use Livewire\WithPagination;
use App\Models\PeriodSelector;
use App\Models\Branch;

class LeadsourceShow extends Component
{
    use WithPagination, PeriodSelector;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search ='';
    public $setPeriod = 'thisWeek';

    public $paginationTheme = 'bootstrap';

    public LeadSource $leadsource;


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
    public function mount(Leadsource $leadsource)
    {
        $this->leadsource = $leadsource;
    }
    public function render()
    {
        return view(
            'livewire.leadsource-show',
            [
                'branches'=>Branch::whereHas(
                    'leads', function ($q) {
                        $q->where('lead_source_id', '=', $this->leadsource->id);
                    }
                )
                ->withCount(
                    ["leads",
                    'leads as assigned'=>function ($query) {
                                   $query->where('lead_source_id', $this->leadsource->id)->has('assignedToBranch');
                    },
                    'leads as claimed' => function ($query) {
                                       $query->where('lead_source_id', $this->leadsource->id)->has('claimedByBranch');
                    },
                               
                    'leads as closed' => function ($query) {
                                       $query->where('lead_source_id', $this->leadsource->id)->has('closed');
                    }]
                )->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage), 
                'companies' => $this->_getCompanies(),
            ]
        );
    }

    private function _getCompanies()
    {
        $companies = $this->leadsource->leads->map(
            function ($lead) {
                return ['id'=>$lead->company->id, 'companyname'=> $lead->company->companyname];
            }
        );
        $companies->unique('companyname')->sortBy('companyname');
        $leadCount = $this->leadsource->leads->countBy(
            function ($lead) {
                return $lead->company_id;
            }
        );
        foreach ($companies as $company) {
            $data[$company['id']] 
                =  [
                'count'=>$leadCount[$company['id']],
                                      
                'companyname'=>$company['companyname']
                ];
          
        }
        return $data;
    }
}
