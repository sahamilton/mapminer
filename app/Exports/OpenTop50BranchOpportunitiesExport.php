<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Branch;

class OpenTop50BranchOpportunitiesExport implements FromView
{
    public $period;
    public $branch;

    /**
     * [__construct description]
     * 
     * @param Array      $period [description]
     * @param array|null $branch [description]
     */
    public function __construct(Array $period, array $branch=null)
    {
        $this->period = $period;
        $this->branch = $branch;
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {

        $branches = Branch::summaryStats($this->period);

        if ($this->branch) {
            $branches->whereIn('id', $this->branch);
        }
        $branches->with('manager')
            ->get();
         
        $period = $this->period;
        return view('reports.branchstats', compact('branches', 'period'));
    }
}
