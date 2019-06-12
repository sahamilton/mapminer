<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\Branch;


class BranchManagerExport implements FromView
{
    public function __construct(string $mgr)
    {
        $this->manager = $manager;
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $results = Branch::doesntHave($mgr)->get();
        $title = "Branches without " . $mgr;
        return view('branches.nomanager', compact('result', 'title'));
    }
}
