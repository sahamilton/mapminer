<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Branch;

class BranchStatsExport implements FromView
{
    public $period;


    public function __construct(Array $period)
    {
    	$this->period = $period;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

		$branches = Branch::summaryStats($this->period)
			->with('manager')
			->get();
		
		$period = $this->period;
		return view('reports.branchstats',compact('branches','period'));
	}
}
