<?php

namespace App\Exports\Reports\Branch;

use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OpenTop25BranchOpportunitiesExport implements FromView
{
    public $period;
    public $branch;

    /**
     * [__construct description].
     *
     * @param array      $period [description]
     * @param array|null $branch [description]
     */
    public function __construct(array $period, array $branch = null)
    {
        $this->period = $period;
        $this->branch = $branch;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $branches = Branch::agingOpportunities($this->period);

        if ($this->branch) {
            $branches = $branches->whereIn('id', $this->branch);
        }
        $branches = $branches->with('manager')
            ->get();

        $period = $this->period;

        return view('reports.agingOpportunities', compact('branches', 'period'));
    }
}
