<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchPipelineExport implements FromView
{
    
   
    public $branches;
    
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Array $period = null, Array $branches=null)
    {
        $this->branches = $branches;
       
       
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
       
        $branches = Branch::with('manager')->whereIn('id', array_keys($this->branches))->get();
      
       
        $query = "select branches.id,
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
       
        $results = \DB::select($query);              
        $period = $this->_createPeriods();
        return view('reports.branchpipeline', compact('results', 'period', 'branches'));
    }
    /**
     * [_createPeriods Create an array of the 6 months into the future]
     * 
     * @return [type] [description]
     */
    private function _createPeriods()
    {
        $start = new Carbon('first day of this month');
        
        for ($i = 0; $i <= 5; $i++) {
            $period[$i] = $start->format('Ym');
            $start->addMonth()->format('Ym');
        }
        
        
        return $period;
    }
}
