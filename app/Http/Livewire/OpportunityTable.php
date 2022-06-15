<?php

namespace App\Http\Livewire;
use App\Opportunity;
use App\Branch;
use App\Person;
use App\Campaign;
use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;

class OpportunityTable extends Component
{
    use WithPagination, PeriodSelector;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'opportunities.created_at';
    public $sortAsc = true;
    public $search = '';
    public $campaign_id = 'all';
    public $setPeriod = "allDates";
    public $branch_id;
    public $filter = '0';
    public $myBranches;
    public $selectuser = 'All';
    public $expected = 'all';
    public Array $expectedRange = [];


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingExpected()
    {
        $this->_getExpectedDatesRange();
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
     * @return [type] [description]
     */
    public function mount($branch_id=null)
    {
                
        //$this->period = session('period');
        $person = new Person();
        $this->myBranches = $person->myBranches();
        if ($branch_id) {
            $this->branch_id =$branch_id;
        } else {
            $this->branch_id = array_key_first($this->myBranches);
        }
        $this->team = Branch::with('branchTeam')
            ->findOrFail($this->branch_id)
            ->branchTeam->pluck('full_name', 'user_id')
            ->toArray();
        $this->setPeriod = 'allDates';
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->_setPeriod();
        $this->_getExpectedDatesRange();
        
        return view(
            'livewire.opportunity-table', 
            [
                'opportunities' => Opportunity::query()
                    ->select('opportunities.*', 'businessname')
                    ->join('addresses', 'addresses.id', '=', 'opportunities.address_id')

                    ->withLastactivity()
                    ->when(
                        $this->search, function ($q) {
                            $q->search($this->search);
                        }
                    )
                    ->when(
                        $this->campaign_id != 'all', function ($q) {
                            $q->whereHas(
                                'location', function ($q) {
                                    $q->whereHas(
                                        'campaigns', function ($q) {
                                            $q->whereIn('campaigns.id', [$this->campaign_id]);
                                        }
                                    );
                                }
                            );
                        }
                    )
                    ->when(

                        $this->expected !='all', function ($q) {
                            $q->whereBetween('expected_close', [$this->expectedRange['from'], $this->expectedRange['to']]);
                        }

                    )
                    ->when(
                        $this->selectuser != 'All', function ($q) {
                            $q->where('opportunities.user_id', $this->selectuser);
                        }
                    )
                    ->when(
                        $this->setPeriod !='All', function ($q) {
                            $q->whereBetween('opportunities.created_at', [$this->period['from'], $this->period['to']]);
                        }
                    ) 
                    ->when(
                        $this->filter != 'All', function ($q) {
                            $q->where('closed', $this->filter);
                        }
                    )
                    ->where('branch_id', $this->branch_id)
                    ->distinct()
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'branch'=>Branch::query()->findOrFail($this->branch_id),
                'filters' => ['All'=>'All', 0=>'Open', '1'=>'Closed Won', '2'=>'Closed Lost'],
                'expecteddates'=>[
                    'all'=>'All', 
                    'lastYear'=>'Last Year or Earlier', 
                    'lastQtr'=> 'Last Qtr', 
                    'lastMonth'=> 'Last Month', 
                    'thisMonth'=> 'This Month',
                    'thisQtr' =>'This Quarter', 
                    'nextMonth'=> 'Next Month',
                    'nextQtr' => 'Next Qtr',
                    'future' =>'After Next Qtr',
                ],

                'campaigns'=> Campaign::active()
                    ->current([$this->branch_id])
                    ->pluck('title', 'id')
                    ->toArray(),
                           

                
            ]
        );
    }

    private function _setPeriod()
    {
        $this->livewirePeriod($this->setPeriod);
      
        
    }

    private function _getExpectedDatesRange()
    {
        if ($this->expected != 'all') {
            
            switch($this->expected) {

                case 'lastYear':
                    $this->expectedRange = ['from'=>now()->subYear(4)->startOfYear(), 'to'=>now()->subYear(1)->endOfYear()];
                    break;
                
                case 'lastQtr':
                    $this->expectedRange = ['from'=>now()->subMonth(3)->startOfQuarter(), 'to'=>now()->subMonth(3)->endOfQuarter()];
                    break;
                
                case 'lastMonth':
                    $this->expectedRange = ['from'=>now()->subMonth(1)->startOfMOnth(), 'to'=>now()->subMonth(1)->endOfMonth()];
                    break;
                
                case 'thisMonth':
                    $this->expectedRange = ['from'=>now()->startOfMonth(), 'to'=>now()->endOfMonth()];
                    break;
                
                case 'thisQuarter':
                    $this->expectedRange = ['from'=>now()->startOfQuarter(), 'to'=>now()->endOfQuarter()];
                    break;
                case'nextMonth':
                    $this->expectedRange = ['from'=>now()->addMonth(1)->startOfMOnth(), 'to'=>now()->addMonth(1)->endOfMonth()];
                    break;
                case 'nextQtr':
                    $this->expectedRange = ['from'=>now()->addMonth(3)->startOfQuarter(), 'to'=>now()->addMonth(3)->endOfQuarter()];
                    break;
                case 'future':
                    $this->expectedRange = ['from'=>now()->addMonth(3)->endOfQuarter(), 'to'=>now()->addYear(3)];
                    break;
                
            }
              



        }

    }
}
