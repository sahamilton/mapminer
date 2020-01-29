<?php

namespace App\Exports;

use App\Branch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchStatsExport implements FromView
{
    public $period;
    public $branches;

    /**
     * [__construct description].
     *
     * @param array $period [description]
     */
    public function __construct(array $period, array $branches = null)
    {
        $this->period = $period;
        $this->branches = $branches;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        if ($this->branches) {
            $branch = Branch::whereIn('id', array_keys($this->branches));
        } else {
            $branch = new Branch;
        }

        $branches = $branch->summaryStats($this->period)
            ->with('manager')->get();

        $period = $this->period;

        return view('reports.branchstats', compact('branches', 'period'));
    }
}
