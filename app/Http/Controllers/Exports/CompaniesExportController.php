<?php

namespace App\Http\Controllers\Exports;

use Illuminate\Http\Request;
use App\Company;
use App\Address;
use \Excel;
use App\Http\Controllers\BaseController;
use App\Exports\CompanyWithLocationsExport;

class CompaniesExportController extends BaseController
{
    public $company;
    public $address;
    public function __construct(Company $company,Address $address)
    {
        $this->company = $company;
        parent::__construct($address);
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = $this->company
            ->whereHas(
                'serviceline', function ($q) {
                    $q->whereIn('serviceline_id', $this->userServiceLines);

                }
            )
            
            ->orderBy('companyname')
            ->pluck('companyname', 'id');

        return response()->view('locations.export', compact('companies'));
    }


    public function export(Request $request)
    {
       
        $company = $this->company->findOrFail(request('company'));
        return Excel::download(new CompanyWithLocationsExport($company), $company->companyname . ' locations.csv');
    }
}
