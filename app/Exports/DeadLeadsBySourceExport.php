<?php

namespace App\Exports;

use App\Branch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DeadLeadsBySourceExport implements FromView
{
    public $branches;
    public $period;

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
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $branches = Branch::deadLeadsBySource(
            $this->branches, $this->period
        );

        $period = $this->period;

        return view('reports.deadleadsbysource', compact('branches', 'period'));
    }
}
