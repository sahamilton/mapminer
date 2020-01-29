<?php

namespace App\Exports;

use App\Branch;
use App\Person;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DailyBranchExport implements FromView
{
    public $period;
    public $branches;
    public $person;

    /**
     * [__construct description].
     *
     * @param array $period [description]
     */
    public function __construct(array $period, array $person)
    {
        $this->period = $period;
        $this->person = $person;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $person = Person::whereIn('id', $this->person)->firstOrFail();
        $myBranches = $person->getMyBranches();

        $branches = Branch::summaryStats($this->period)
            ->with('manager', 'manager.reportsTo')
            ->whereIn('id', $myBranches)->get();

        $period = $this->period;

        return view('reports.dailybranchstats', compact('branches', 'period', 'person'));
    }
}
