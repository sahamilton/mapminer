<?php

namespace App\Exports;

use App\Opportunity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
class Top50WeekReportExport implements FromView
{
    public $period;


    public function __construct(Carbon $period)
    {
    	$this->period = $period;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
     	$period = $this->period;
        $opportunities = Opportunity::where('closed','=',0)
		        ->where('top50','=',1)
		        ->where(function($q){
		        	$q->whereNull('actual_close')
		        	->orWhere('actual_close','>',$this->period);
		        })
		        
		        ->select('branch_id', \DB::raw('count(id) as total,sum(value) as sumvalue'))
		        ->groupBy('branch_id')
		        ->get();

        return view('reports.weeklyreport',compact('opportunities','period'));
    }
}