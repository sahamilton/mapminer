<?php

namespace App\Exports;

use App\LeadSource;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeadSourceExport implements FromView
{
    public $leadsource;
    public $statuses;

    public function __construct(LeadSource $leadsource, $statuses)
    {
        $this->leadsource = $leadsource;
        $this->statuses = $statuses;
    }
    
    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
 		/*Excel::download('Prospects'.time(), function ($excel) use ($id) {
            $excel->sheet('Prospects', function ($sheet) use ($id) {
               
                $sheet->loadView('leadsource.export', compact('leadsource', 'statuses'));
            });
        })->download('csv');*/

 		$leadsource = $this->leadsource;
 		$statuses = $this->statuses;
 	
       return view('leadsource.export', compact('leadsource', 'statuses'));
    }
}