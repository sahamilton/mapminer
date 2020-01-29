<?php

namespace App\Exports;

use App\Branch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchOpportunitiesExport implements FromView
{
    public $period;
    public $branches;

    /**
     * [__construct description].
     *
     * @param array      $period   [description]
     * @param array|null $branches [description]
     */
    public function __construct(array $period, array $branches = null)
    {
        $this->period = $period;
        $this->branches = $branches;
    }

    /**
     * View.
     *
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $branches = Branch::branchOpenOpportunities($this->period)
            ->with('manager');

        if ($this->branches) {
            $branches = $branches->whereIn('id', $this->branches);
        }
        $branches = $branches->get();

        $period = $this->period;

        return view('reports.branchopportunities', compact('branches', 'period'));
    }
}
