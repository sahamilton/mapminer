<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Branch;

class BranchOpportunitiesExport implements FromView
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
     * View
     * 
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        
        $branches = Branch::branchOpportunities($this->period)
            ->with('manager', 'manager.reportsTo')
            ->get();

        $period = $this->period;
        return view('reports.branchopportunities', compact('branches', 'period'));

    }
}
