<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Branch;

class BranchOpportunitiesExport implements FromView
{
    public $period;
    public $branches;

    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Array $period, Array $branches)
    {
        $this->period = $period;
        $this->branches = $branches;
    }

    /**
     * View
     * 
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        
        $branches = Branch::branchOpenOpportunities($this->period)
            ->with('manager');
        if ($this->branches) {
            $branches->whereIn('id', $this->branches);
        }   
        $branches->get();
        

        $period = $this->period;
        return view('reports.branchopportunities', compact('branches', 'period'));

    }
}
