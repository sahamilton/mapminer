<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchLoginsExport implements FromView
{
    
    public $period;
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Array $period)
    {
        $this->period = $period;
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $query= "select branches.id as branchid, branchname, count(track.id) as logins,
        	count(track.id)/datediff( '". $this->period['to'] ."','". $this->period['from']."') as avgdaily
			from track, persons,branch_person, branches
			where track.created_at between '". $this->period['from'] ."' and '". $this->period['to']."'
			and track.user_id = persons.user_id
			and persons.id = branch_person.person_id
			and branch_person.branch_id = branches.id
			group by branchname";
        $results = \DB::select($query);
   
        $period = $this->period;
        return view('reports.branchlogins', compact('results', 'period'));
    }
}
