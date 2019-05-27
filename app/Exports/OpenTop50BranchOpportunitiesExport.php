<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Branch;

class OpenTop50BranchOpportunitiesExport implements FromView
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

        $branches = Branch::summaryStats($this->period)
            ->with('manager')
            ->get();

        $period = $this->period;
        return view('reports.branchstats', compact('branches', 'period'));
    }
}