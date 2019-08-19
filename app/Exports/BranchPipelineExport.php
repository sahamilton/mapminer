<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchPipelineExport implements FromView
{
    
   
    public $branches;
    public $period;
    
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Array $period = null, Array $branches=null)
    {
        $this->branches = $branches;
        if (! $period) {
            $this->period['from']= now()->startOfWeek();
            $this->period['to'] = now()->addWeeks(8)->endOfWeek();
        } else {
            $this->period = $period;
        }
       
       
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        
        $branches = Branch::with('manager')
            ->whereIn('id', array_keys($this->branches))
            ->with(
                ['opportunities' => function ($q)  {
                    $q->whereBetween('expected_close', [$this->period['from'], $this->period['to']])
                        ->where('closed', 0)
                        ->selectRaw('FROM_DAYS(TO_DAYS(expected_close) -MOD(TO_DAYS(expected_close) -2, 7)) as yearweek, sum(value) as funnel')
                        ->groupBy('expected_close');
                }
                ]
            )
            ->get();
       
       
       /* $query = "select branches.id,
             DATE_FORMAT(opportunities.expected_close,'%Y%m') 
              as month, 
              sum(opportunities.value) as value
                from opportunities, branches
                 where opportunities.branch_id = branches.id
                 and opportunities.closed = 0
                 and expected_close is not null
                 and expected_close >= '". now()->startOfMonth() . "'
                 and value > 0
                 and branches.id in ('" . implode("','", array_keys($this->branches)) ."')
                 group by month, branchname order by branchname, month";
       
        $results = \DB::select($query);  */            
        $periods = $this->_createPeriods();
        $period = $this->period;
        return view('reports.branchpipeline', compact('periods', 'period', 'branches'));
    }
    /**
     * [_createPeriods Create an array of the 6 months into the future]
     * 
     * @return [type] [description]
     */
    private function _createPeriods()
    {
        $start = clone($this->period['from']);
        $end = clone($this->period['to']);
        $pers = array();
        for ($i = 0; $start <= $end; $i++) {

            $pers[$i] = clone($start);
            $pers[$i] = $pers[$i]->format('Y-m-d');
            $start->addWeek();

        }

        return $pers;
    }
}
