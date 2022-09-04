<?php

namespace App\Exports;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CompaniesExport implements FromView
{
    public function __construct()
    {

        
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        $companies = Company::with('industryVertical', 'managedBy', 'serviceline', 'type')

                ->get();

        return view('companies.exportcompanies', compact('companies'));
    }
}
