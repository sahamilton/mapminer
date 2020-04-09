<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Branch;
use App\Person;

class DailyBranchExport implements FromView
{
    public $period;
    public $branches;
    public $person;

    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Array $period, Array $person)
    {
        $this->period = $period;
        $this->person = $person;
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        
        $person = Person::whereIn('id', $this->person)->firstOrFail();
        $myBranches= $person->getMyBranches();
        $branches = Branch::summaryStats($this->period)
            ->with('manager', 'manager.reportsTo')
            ->whereIn('id', $myBranches)->get();
        
        $period = $this->period;
        return view('reports.dailybranchstats', compact('branches', 'period', 'person'));
    }
}
