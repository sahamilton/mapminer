<?php

namespace App\Exports;

use App\Opportunity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
class ActivityOpportunityExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
     	$period =Carbon::now()->endOfWeek();
        

        return view('reports.actopptyreport',compact('results','period'));
    }
}
