<?php

namespace App\Http\Controllers\Imports;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\LeadSource;
use App\Models\Branch;
use App\Models\LocationImport;
use App\Models\Serviceline;
use App\Http\Requests\LocationImportFormRequest;
use Excel;

class LeadSourceImportController extends ImportController
{
    public $location;
    public $company;
    public $import;
    public $leadsource;

    public function __construct(LeadSource $leadsource, Company $company, LocationImport $import)
    {
        $this->leadsource = $leadsource;
        $this->company = $company;
        $this->import = $import;
    }

    public function getFile(LeadSource $leadsource)
    {
        
        $requiredFields = $this->import->requiredFields;
       // $branches = Branch::orderBy('id')->get();
        $companies = $this->company->orderBy('companyname')->pluck('companyname', 'id');
        $servicelines = Serviceline::all();
        return response()->view('leadsource.import', compact('companies', 'requiredFields', 'servicelines', 'leadsource'));
    }

    
}
