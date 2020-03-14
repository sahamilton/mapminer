<?php

namespace App\Exports;
use App\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StaleOpportunitiesSummaryExport implements FromView
{   
    
    public $branches;
    public $period;

    /**
     * [__construct description]
     * 
     * @param Array|null $branch [description]
     */
    public function __construct(array $period, Array $branches)
    {
       
        $this->branches = $branches;
        $this->period = $period;
       
        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $branches = Branch::withCount('staleOpportunities')->whereIn('id', array_keys($this->branches))->get();
        return view('opportunities.stale', compact('branches'));
    }
}
