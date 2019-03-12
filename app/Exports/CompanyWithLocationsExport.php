<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Company;

class CompanyWithLocationsExport implements FromView
{
    public function __construct(Company $company)
    {
        $this->company = $company;
      
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	$company = $this->company->load('locations');
    	return view('locations.exportlocations',compact('company'));

    }
}