<?php

namespace App\Exports;

use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OpenTop50BranchOpportunitiesExport implements FromView
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
        $branches = Branch::agingOpportunities($this->period)
        ->when(
            $this->branch, function ($q) {
                $q->whereIn('id', $this->branch);
            }
        )->with('manager.reportsTo')
            ->get();

        return view('reports.agingOpportunities', ['branches'=>$branches, 'period'=>$this->period]);
    }
}
