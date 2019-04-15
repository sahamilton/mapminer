<?php

namespace App\Exports;

use App\Opportunity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
class ActivityOpportunityExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
     	$period['from'] = Carbon::now()->startOfWeek();
     	$period['to'] = Carbon::now()->endOfWeek();
        $query = 
        "select branches.id as branch_id,
	        branches.branchname as branchname, 
	        a.salesmeetings,
	        b.opportunitieswon,
	        b.value 

	        from branches

			left join 
				(select branch_id, count(activities.id) as salesmeetings 
				 from activities 
				 where activities.activitytype_id = 4 
				 and activities.activity_date between '"
				 . $period['from']. 
				 "' and '"
				 .$period['to'] .
				 "' group by activities.branch_id) a 
			 
			 on branches.id = a.branch_id
			 
			left join (
				     select opportunities.branch_id, count(opportunities.id) as opportunitieswon,sum(value) as value 
				     from opportunities 
				     where opportunities.closed = 1 
				     and opportunities.actual_close between '"
				     . $period['from']. 
				 		"' and '"
				 	.$period['to'] .
				     "' group by opportunities.branch_id) b 
			     
			     on branches.id = b.branch_id  
			ORDER BY branches.id  ASC ";
	
		$results = \DB::select($query);

        return view('reports.actopptyreport',compact('results','period'));
    }
}
