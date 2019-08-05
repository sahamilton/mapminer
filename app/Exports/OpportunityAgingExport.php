<?php

namespace App\Exports;

use App\Opportunity;
use Carbon\Carbon;
use App\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class OpportunityAgingExport implements FromView
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
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
 		$data = $this->data;
       return view('persons.export',compact('data'));
    }
}