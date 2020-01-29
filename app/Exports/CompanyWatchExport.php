<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyWatchExport implements FromView
{
    public $location;
    public $accounts;

    /**
     * [__construct description].
     *
     * @param array $period [description]
     */
    public function __construct(Collection $location)
    {
        $this->location = $location;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $result = $this->location;

        return view('watch.companyexport', compact('result'));
    }
}
