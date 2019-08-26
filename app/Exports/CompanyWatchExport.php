<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Database\Eloquent\Collection;


class CompanyWatchExport implements FromView
{
    public $location;
    public $accounts;

    /**
     * [__construct description]
     * 
     * @param Array $period [description]
     */
    public function __construct(Collection $location)
    {
        $this->location = $location;
        
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        
        $result = $this->location; 

        return view('watch.companyexport', compact('result'));
       
    }
}
