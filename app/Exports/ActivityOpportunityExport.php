<?php

namespace App\Exports;

use App\Opportunity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
class ActivityOpportunityExport implements FromView
{
    public $period;
    public $branch;

    /**
     * [__construct description]
     * 
     * @param array $period [description]
     */
    public function __construct(array $period, array $branches=null)
    {
        $this->period = $period;
        $this->branch = $branches;
    }


    /**
    public function view(): View
    {


            left join 
                (select branch_id, count(activities.id) as salesmeetings 
                 from activities 
                 where activities.activitytype_id = 4 
                 and activities.completed is not null
                 and activities.activity_date between '"
                 . $this->period['from']. 
                 "' and '"
                 .$this->period['to'] .
                 "' group by activities.branch_id) a 
             
             on branches.id = a.branch_id
             
            left join (
                     select opportunities.branch_id, count(opportunities.id) as opportunitieswon,sum(value) as value 
                     from opportunities 
                     where opportunities.closed = 1 
                     and opportunities.actual_close between '"
                     . $this->period['from'] . 
                        "' and '"
                    . $this->period['to'] .
                     "' group by opportunities.branch_id) b 
                 
                 on branches.id = b.branch_id";
        if ($this->branch) {
            $query.=" and branches.id in ('". implode("','", $this->branch) ."') ";
        }  
        $query.=" ORDER BY branches.id  ASC ";
    
        $results = \DB::select($query);
        $period = $this->period;
        return view('reports.actopptyreport', compact('results', 'period'));
    }
}
