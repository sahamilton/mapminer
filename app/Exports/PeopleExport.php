<?php

namespace App\Exports;

use App\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class PeopleExport implements FromView
{
    public $data;

    public function __construct($people)
    {
        $this->data = $people;
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
    

