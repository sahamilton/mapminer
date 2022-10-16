<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Branch;

use Livewire\WithPagination;


class BranchRequirements extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'branchname';
   
    public $sortAsc = true;
    public $search ='';
    public array $myBranches;
    public array $reportPeriod =[];
    public $duration = 10;
    public $view = 4;
    public array $closed =[1,2];
    public $periodView = 'week';


    public function updatedView($view)
    {
        
        if ($view == 4) {
            
            $this->closed = [0,1];
        } else {
            
            $this->closed = [$view];
        }
       
    }
    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    /**
     * [sortBy description]
     * 
     * @param STR $field [description]
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
     * @param Branch|null $branch [description]
     * 
     * @return [type]              [description]
     */
    public function mount(Branch $branch=null)
    {
        $this->myBranches = auth()->user()->person->getMyBranches();
        $this->_getReportPeriod();

    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render() 
    {
      
        $this->_getReportPeriod();
        return view(
            'livewire.branch.branch-requirements',
            [
                'branches'=> Branch::query()
                    ->with('manager')
                    ->periodOpportunities(['from'=>$this->reportPeriod[0]['from'], 'to'=>$this->reportPeriod[$this->duration-1]['to']], $this->closed)
                    ->whereIn('id', $this->myBranches)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->get()
                    ->paginate($this->perPage),
                    'views'=>[4=>'Won and Open Opportunities',1=>'Won Opportunities Only', 0=>'Open Opportunities Only'],
                    'periodViews'=>['day'=>'Daily', 'week'=>'Weekly', 'month'=>'Monthly'],

            ]
        );
    }
    /**
     * [_getReportPeriod description]
     * 
     * @return [type] [description]
     */
    private function _getReportPeriod()
    {

        $data= [];
        for ($i=0;$i <= $this->duration; $i++ ) {

            $this->_getPeriodData($i);

        }

    }

    private function _getPeriodData($i)
    {

        switch($this->periodView) {
        case 'month':
            $this->reportPeriod[$i]['from'] = now()->addMonth($i)->startOfMonth();
            $this->reportPeriod[$i]['to'] = now()->addMonth($i)->endOfMonth();

            break;


        case 'week':
            $this->reportPeriod[$i]['from'] = now()->addWeek($i)->startOfWeek();
            $this->reportPeriod[$i]['to'] = now()->addWeek($i)->endOfWeek();

            break;


        case 'day':
            $this->reportPeriod[$i]['from'] = now()->addDay($i)->startOfDay();
            $this->reportPeriod[$i]['to'] = now()->addDay($i)->endOfDay();
            break;


        }
    }
}

