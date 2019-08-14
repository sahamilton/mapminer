<?php

namespace App\Exports;

use App\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PeopleExport implements FromView
{
    public $data;
    /**
     * [__construct description]
     * 
     * @param [type] $people [description]
     */
    public function __construct($people)
    {
        $this->data = $people;
    }
    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $data = $this->data;
        return view('persons.export', compact('data'));
    }
}