<?php

namespace App\Exports;

use App\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class TeamLoginsExport implements FromView
{
    public $people;

    public function __construct($people)
    {
        $this->people = $people;
    }
    
    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
 		$people = $this->people;
       return view('team.export',compact('people'));
    }
}