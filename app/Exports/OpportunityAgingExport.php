<?php

namespace App\Exports;

use App\Branch;
use App\Opportunity;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OpportunityAgingExport implements FromView
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
        $data = $this->data;

        return view('persons.export', compact('data'));
    }
}
