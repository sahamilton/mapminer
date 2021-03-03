<?php

namespace App\Http\Controllers;

use App\Company;
use App\CompanyImport;
use Illuminate\Http\Request;

class CompaniesImportController extends ImportController
{
    public $project;
    public $sources;
    public $import;

    public function __construct(Company $company, CompanyImport $import)
    {
        $this->company = $company;
        $this->import = $import;
    }

    public function getFile(Request $request)
    {
        $requiredFields = $this->import->requiredFields;

        return response()->view('companies.import', compact('requiredFields'));
    }

    public function import(Request $request)
    {
        $data = $this->uploadfile(request()->file('upload'));
        $data['table'] = 'customerimport';
        $data['route'] = 'companies.mapfields';
        $data['additionaldata'] = [];
        $data['type'] = null;
        $fields = $this->getFileFields($data);

        $columns = $this->import->getTableColumns('customerimport');

        $skip = ['id', 'created_at', 'updated_at'];
        $requiredFields = $this->import->requiredFields;

        return response()->view('imports.mapfields', compact('columns', 'fields', 'data', 'skip', 'requiredFields'));
    }

    public function mapfields(Request $request)
    {
        $data = $this->getData($request);

        if ($multiple = $this->import->detectDuplicateSelections(request('fields'))) {
            return redirect()->route('companies.importfile')->withError(['You have mapped a field more than once.  Field: '.implode(' , ', $multiple)]);
        }
        if ($missing = $this->import->validateImport(request('fields'))) {
            return redirect()->route('companies.importfile')->withError(['You have to map all required fields.  Missing: '.implode(' , ', $missing)]);
        }
        $this->import->setFields($data);

        if ($this->import->import()) {

            //map to see if any are already in existance
            //
            //Do not delete import file
            //check for duplicates based on lat lng (position?)
            //then copy new ones to addresses.
            // copy $$ to companyorders period
            return redirect()->route('orderimport.index');
        }

        //SELECT distinct customerimport.companyname,customerimport.customer_id FROM `customerimport` left join companies on customerimport.customer_id = companies.customer_id where companies.customer_id is null

        //SELECT locations.id, customerimport.id, locations.businessname, companies.companyname, locations.street,locations.city, customerimport.companyname,customerimport.street,customerimport.city from customerimport, locations left join companies on locations.company_id = companies.id where locations.lat = customerimport.lat and locations.lng = customerimport.lng and accuracy = 1
    }
}
