<?php

namespace App\Exports;
use App\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StaleLeadsSummaryExport implements FromView
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
        $branches = Branch::withCount('staleLeads')->whereIn('id', $this->branches)->get();
        return view('leads.stale', compact('branches'));
    }
}
