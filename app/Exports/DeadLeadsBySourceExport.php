<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Branch;

class DeadLeadsBySourceExport implements FromView
{
    
    public $branches;
    public $period;

    /**
     * [__construct description]
     * 
     * @param Array      $period   [description]
     * @param Array|null $branches [description]
     */
    public function __construct(Array $period, Array $branches=null)
    {
        $this->period = $period;
        $this->branches = $branches;

    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        
        $branches = Branch::deadLeadsBySource(
            array_keys($this->branches), $this->period
        );
        
        $period = $this->period;
        return view('reports.deadleadsbysource', compact('branches', 'period'));
    }
}
