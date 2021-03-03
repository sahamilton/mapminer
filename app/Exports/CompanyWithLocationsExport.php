<?php

namespace App\Exports;

use App\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyWithLocationsExport implements FromView
{
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $company = $this->company->load('locations');

        return view('locations.exportlocations', compact('company'));
    }
}
