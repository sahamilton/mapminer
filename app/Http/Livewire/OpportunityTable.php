<?php

namespace App\Http\Livewire;
use App\Models\Opportunity;
use App\Models\Branch;
use App\Models\Person;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeriodSelector;

use Carbon\Carbon;

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


    public $closeOpportunityModal = false;
    public Opportunity $opportunity;

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
            'livewire.opportunities.opportunity-table', 
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
    /**
     * [editOpportunity description]
     * 
     * @param Opportunity $opportunity [description]
     * @param [type]      $action      [description]
     * 
     * @return [type]                   [description]
     */
    public function editOpportunity(Opportunity $opportunity, $action)
    {
        if ($action === 'close') {

            
            $this->opportunity = $opportunity;
            $this->opportunity->closed = null;
            $this->opportunity->actual_close = @now()->format('Y-m-d');
            $this->opportunity->comments = null;
            $this->doShow('closeOpportunityModal');
        }
       
    }
    /**
     * [closeOpportunity description]
     * 
     * @param Opportunity $opportunity [description]
     * 
     * @return [type]                   [description]
     */
    public function closeOpportunity()
    {
        
        
        $this->validate();
        $this->opportunity->save();
        $this->doClose('closeOpportunityModal');
        
        $this->opportunity->update(['actual_close' => Carbon::parse($this->opportunity->actual_close)]);
        $this->_setEstStartEndDates();

    }
    private function _setEstStartEndDates()
    {

        if ($this->opportunity->closed == 1) {
            $data['est_start'] = $this->opportunity->actual_close;
            $data['est_end'] = $this->opportunity->actual_close->addMonths($this->opportunity->duration);
        } elseif ($this->opportunity->closed == 0) {
            $data['est_start'] = $this->opportunity->expected_close;
            $data['est_end'] = $this->opportunity->expected_close->addMonths($this->opportunity->duration);
        } else {
            $data['est_start'] = null;
            $data['est_end'] = null;

        }
     
        $this->opportunity->update($data);

    }
    /**
     * [doShow description]
     * 
     * @param [type] $form [description]
     * 
     * @return [type]       [description]
     */
    public function doShow($form=null)
    {
      
        $this->$form =true;
        
    }
    /**
     * [doClose description]
     * 
     * @param [type] $form [description]
     * 
     * @return [type]       [description]
     */
    public function doClose($form=null)
    {
        $this->$form= false;
               
        
    }

    /**
     * [rules description]
     * 
     * @return [type] [description]
     */
    public function rules()
    {
        


        return [

            'opportunity.comments'=>'required',
            
            'opportunity.closed'=>'in:1,2',
            'opportunity.actual_close' => 'required|date|before:tomorrow',
            'opportunity.est_start' =>'sometimes|date',
            'opportunity.est_end' =>'sometimes|date',

            

        ] ;
    }
}
