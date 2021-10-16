<?php

namespace App\Http\Controllers\Imports;

use Illuminate\Http\Request;
use App\Company;
use App\Address;
use App\Branch;
use App\Model;
use App\LeadSource;
use App\LocationImport;
use App\Serviceline;
use App\Http\Requests\LocationImportFormRequest;
use Excel;

class LocationsImportController extends ImportController
{
    public $location;
    public $company;
    public $import;
    public $table = 'addresses';
    public $temptable = 'addresses_import';
    public function __construct(Address $location, Company $company, LocationImport $import)
    {
        $this->location = $location;
        $this->company = $company;
        $this->import = $import;
    }

    public function getFile()
    {
        
        $requiredFields = $this->import->requiredFields;
        
        $companies = $this->company->orderBy('companyname')->pluck('companyname', 'id');
        $servicelines = Serviceline::all();
        $leadsources = LeadSource::active()->orderBy('source')->get();
        return response()->view('locations.import', compact('companies', 'requiredFields', 'servicelines', 'leadsources'));
    }

    /**
     * [import description]
     * 
     * @param LocationImportFormRequest $request [description]
     * 
     * @return [type]                             [description]
     */
    public function import(Request $request) 
    {
       
        $data = request()->except('_token');
        
        $title="Map the locations import file fields";
        $data = array_merge($data, $this->uploadfile(request()->file('upload')));
        $data['additionaldata'] = null;
        $data['route'] = 'locations.mapfields';
        // only create a lead source if non included and no company selected.
        if (! request()->filled('lead_source_id')) {

            $data['lead_source_id'] = $this->import->createLeadSource($data);
        }
        
        if (request()->filled('company')) {
                $data['additionaldata']['company_id'] = request('company');
                $this->import->setDontCreateTemp(true); 
                $company_id = request('company');
        } else {
                $this->import->setDontCreateTemp(false);
                $company_id = null;
        }
        $this->import->tempTable = $this->temptable;
       
        $fields = $this->getFileFields($data);
        $skip = ['id','created_at','updated_at','lead_source_id','serviceline_id','addressable_id','user_id','addressable_type','import_ref'];
        $columns = $this->location->getTableColumns($this->table, $skip);
        $requiredFields = $this->import->requiredFields;

        if (isset($data['contacts'])) {
            $skip = ['id','created_at','updated_at','address_id','location_id','user_id'];
            $columns = array_merge($columns, $this->location->getTableColumns('contacts', $skip));
        }
       
        if (isset($data['branch'])) {
            $data['branch_ids'] = implode(',', $data['branch']);
        }
        $columns[] = (object) ['Field'=>'branch_id','Type'=>'varchar(20)'];
       

        return response()->view(
            'imports.mapfields', compact(
                'columns',
                'fields',
                'data',
                'company_id',
                'title',
                'requiredFields'
            )
        );
    }
    /**
     * [mapfields description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function mapfields(Request $request)
    {
        
        $data = $this->getData($request);
        
        if ($error = $this->validateInput($request)) {
            return redirect()->route('locations.importfile')->withError($error)->withInput($data);
        }
        $data['table']=$this->table;
        
        
        if (request('type') != 'location') {
            $data['dontCreateTemp'] =false; 
            
        } else {
            $data['dontCreateTemp'] =true;
            $data['tempTable'] = $this->temptable;
        }
        $this->import->setFields($data);
        if ($fileimport = $this->import->import($request)) {
            
            if (request()->has('company') or request('type') == 'location') {
                return redirect()->route('postprocess.index');
                /// copy from import table to addresses
                /// return to company view
            }

            return redirect()->route('leadsource.show', request('lead_source_id'))->with('success', 'Locations imported');
        }
    }
    
}
