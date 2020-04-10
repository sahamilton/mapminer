<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchLoginsExport implements FromView
{
    
    public $period;
    public $branches;
    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Array $period, Array $branches=null)
    {
        $this->period = $period;
        $this->branches = $branches;

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
            and branch_person.branch_id = branches.id ";
        if ($this->branches) {
            $query.= " and branches.id in ('".implode("','", $this->branches) ."')";
        }
        $query.=" group by branches.id, branchname ";

        $results = \DB::select($query);
        
        if ($this->branches) {
            
            $branches = array_keys($this->branches);
        } else {
            
            $branches = $this->branches;
        }
        $period = $this->period;
        return view('reports.branchlogins', compact('results', 'period', 'branches'));
    }
}
