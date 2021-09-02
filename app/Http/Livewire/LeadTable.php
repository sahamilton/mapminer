<?php

namespace App\Http\Livewire;
use App\Address;
use App\Activity;
use App\Branch;
use App\Campaign;
use App\Person;
use App\ActivityType;
use Livewire\Component;
use Livewire\WithPagination;
use App\AddressBranch;
use App\PeriodSelector;
class LeadTable extends Component
{
    use WithPagination, PeriodSelector;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = null;
   
    public $withOps = 'All';
    //public $updateMode = false;
    public $setPeriod;
 
    //public $activity_date;
    public $branch_id;
    public $lead_source_id = 'All';
    public $campaign_id = 'All';
    public $myBranches;

 
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingLeadSourceId()
    {
        $this->resetPage();
    }
    public function updatingBranchId()
    {
        $this->resetPage();
        $this->lead_source_id = 'All';
        $this->campaign_id = 'All';

    }
    /**
     * [sortBy description]
     * 
     * @param [type] $field [description]
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
     * [mount description]
     * 
     * @return [type]         [description]
     */
    public function mount($branch, $search = null)
    {
        $person = new Person();
        $this->myBranches = $person->myBranches();
        $this->search = $search;
        $this->branch_id = array_keys($this->myBranches)[0];
        if (! session()->has('period')) {
            $this-> _setPeriod();
        } 
        $this->setPeriod = session('period')['period'];
        
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->_setPeriod();
        $this->_setBranchSession();
        return view(
            'livewire.lead-table', [
            'leads' => Address::query()
                ->search($this->search)
                ->when(
                    $this->withOps != 'All', function ($q) {
                        $q->when(
                            $this->withOps == 'Without', function ($q) {
                                $q->whereDoesntHave('opportunities');
                            }
                        )
                        ->when(
                            $this->withOps == 'Only Open', function ($q) {
                                $q->whereHas(
                                    'opportunities', function ($q) {
                                        $q->where('closed', 0);
                                    }
                                );
                                
                            }
                        )
                        ->when(
                            $this->withOps == 'Any', function ($q) {
                                $q->has('opportunities');
                            }
                        );
                        
                    }
                )
                ->when(
                    $this->campaign_id != 'All', function ($q) {
                        $q->whereHas(
                            'campaigns', function ($q) {
                                $q->where('campaigns.id', $this->campaign_id);
                            }
                        );
                    }
                )->whereIn(
                    'addresses.id', function ($query) {
                        $query->select('address_id')
                            ->from('address_branch')
                            ->where('branch_id', $this->branch_id)
                            ->where('status_id', 2)
                            ->when(
                                $this->setPeriod != 'All', function ($q) {
                                    $q->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                                }
                            );
                    }
                )
            
                ->with('assignedToBranch')
                ->when(
                    $this->lead_source_id != 'All', function ($q) {
                        $q->where('lead_source_id', $this->lead_source_id);
                    }
                )
                
                ->withLastActivityId()
                ->with('lastActivity')
                ->dateAdded()
                ->orderByColumn($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'branch'=>Branch::query()->with('manager', 'currentcampaigns', 'currentopencampaigns')->findOrFail($this->branch_id),
                'opstatus'=>['All', 'Without', 'Only Open', 'Any'],
                'activities'=>ActivityType::pluck('activity', 'id')->toArray(),
                'leadsources' => $this->_getLeadSources(),
                'campaigns'=> Campaign::active()
                    ->current([$this->branch_id])
                    ->pluck('title', 'id')
                    ->toArray(),
            ]
        );
    }

    private function _setBranchSession()
    {
        session(['branch'=>$this->branch_id]);
    }

    private function _setPeriod()
    {
        $this->livewirePeriod($this->setPeriod);
        
    }

    private function _getLeadSources()
    {
        return collect(
            \DB::select(
                \DB::raw(
                    "select distinct leadsources.id, 
                        leadsources.source 
                        from address_branch, addresses, leadsources 
                        where address_branch.branch_id = ". $this->branch_id .
                        " and addresses.id = address_branch.address_id
                        and address_branch.status_id = 2 
                        and addresses.lead_source_id = leadsources.id"
                )
            )
        )
            ->sortBy('source')
            ->pluck('source', 'id')
            ->toarray();
        
    }
}
