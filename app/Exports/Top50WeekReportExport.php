<?php

namespace App\Exports;

use App\Opportunity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
class Top50WeekReportExport implements FromView
{
    public $period;
    public $branch;

    /**
     * [__construct description]
     * 
     * @param Carbon $period [description]
     */
    public function __construct(Array $period, Array $branch=null)
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
        $period = $this->period;
        $opportunities = Opportunity::where('closed', 0)
            ->orWhere('actual_close', '>', $this->period['to'])
            ->with('branch')

            ->select('branch_id', \DB::raw('count(id) as total,count(top50) as top50, sum(value) as sumvalue'));
        if ($this->branch) {
            $opportunities->whereIn('branch_id', implode("','", $this->branch));
        }

        $opportunities->groupBy('branch_id')->get();
 
        return view('reports.weeklyreport', compact('opportunities', 'period'));
    }
}