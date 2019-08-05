<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class BranchActivitiesDetailExport implements FromView
{
    
    public $period;
    public $branches;
    /**
     * [__construct description]
     * 
     * @param Array      $period   [description]
     * @param Array|null $branches [description]
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
        $period = $this->period;
        $query = "select  a.branchname,
             concat_ws(' ',firstname,lastname) as manager, 
             STR_TO_DATE(concat(CAST(week AS CHAR),' Monday'),'%X%V %W') as weekbegin, 
             a.activity, 
             a.activitycount
            from persons, branch_person, branches, 
            (
                select branchname, 
                concat(YEAR(activity_date),
                WEEK(activity_date)) as week,
                activity, count(activities.id) as activitycount
                from activities, branches, activity_type 
                where activities.activity_date between '"
                .$period['from']."'  and '".$period['to']."' 
                and activities.activitytype_id = activity_type.id 
                and activities.completed =1 
                and branch_id = branches.id ";
        if ($this->branches) {
            $query.= " and branch_id in ('" . implode("','", $this->branches)."')";
        } 
            $query.= " group by branchname, week, activity ) a
                where persons.id = branch_person.person_id
                and branch_person.role_id = 9
                and branch_person.branch_id = branches.id
                and branches.branchname = a.branchname  
                ORDER BY `a`.`week`  ASC";

         $results = \DB::select($query);
         
        return view('reports.branchactivitiesdetail', compact('results', 'period'));
    }
}
