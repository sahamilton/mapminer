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
    public function __construct(Array $period, array $person)
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
        
        $person = Person::where('id', $this->person)->firstOrFail();
        
        $myBranches = $person->myBranches($person);
       
        $branches = Branch::summaryStats($this->period)
            ->with('manager')
            ->whereIn('id', array_keys($myBranches))->get();
       
        $period = $this->period;

        return view('reports.dailybranch', compact('branches', 'period', 'person'));
    }
}
