<?php

namespace App\Exports;

use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchManagerExport implements FromView
{
    public function __construct(string $manager)
    {
        $this->manager = $manager;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $results = Branch::doesntHave($this->manager)->get();
        $title = 'Branches without '.$this->manager;

        return view('branches.nomanager', compact('results', 'title'));
    }
}
