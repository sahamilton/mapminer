<?php

namespace App\Exports;

use App\Models\Person;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MyTeamLoginsExport implements FromView
{
    public $people;

    /**
     * [__construct description].
     *
     * @param [type] $people [description]
     */
    public function __construct($people)
    {
        $this->people = $people;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $people = $this->people;

        return view('team.export', compact('people'));
    }
}
