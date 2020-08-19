<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Address;
use App\LeadImport;
use App\LeadSource;

use App\Http\Requests\LeadImportFormRequest;

class LeadImportController extends ImportController
{
    public $lead;
    public $leadsources;
    public $import;
    
    public $addressfields =[
            'businessname',
            'address',
            'city',
            'state',
            'zip',
            'lat',
            'lng',
            'position',
            'lead_source_id',
            'created_at'];


    public $leadfields =[ 'description'];


    public $leadcontactfields =[
                'lead_id',
                'firstname',
                'lastname',
                'title',
                'email',
                'phone',
                'created_at'
    ];
    /**
     * [__construct description]
     * 
     * @param Address    $address    [description]
     * @param LeadSource $leadsource [description]
     * @param LeadImport $import     [description]
     */
    public function __construct(Address $address, LeadSource $leadsource, LeadImport $import)
    {
        $this->lead = $address;
        $this->import = $import;
        $this->leadsources = $leadsource;
    }
    /**
     * [getFile description]
     * 
     * @param Request         $request    [description]
     * @param LeadSource|null $leadsource [description]
     * @param [type]          $type       [description]
     * 
     * @return [type]                      [description]
     */
    public function getFile(Request $request, LeadSource $leadsource=null,$type=null) 
    {

        $sources= $this->leadsources->all()->pluck('source', 'id');
        if ($sources->count() == 0) {
            return redirect()->route('leadsource.index')->with('error', 'You must create a lead source first');
        }
        
        $requiredFields = $this->lead->requiredfields;
        if ($type=='assigned') {
            $requiredFields[] = 'employeee_number';
        }
       
        return response()->view('leads.import', compact('sources', 'leadsource', 'requiredFields', 'type'));
    }

    /**
     * [import description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function import(Request $request)
    {
        
        $data = $this->uploadfile(request()->file('upload'));
        $title="Map the leads import file fields";
        $requiredFields = $this->import->requiredFields;

        $data['type']=request('type');

        if ($data['type']== 'assigned') {
            $data['table']='addresses_import';
            $requiredFields[]='employee_id';
        } else {
            $data['table']='addresses_import';
        }

        $data['additionaldata'] = request('additionaldata');

        $data['route'] = 'leads.mapfields';
        $fields = $this->getFileFields($data);
        $columns = $this->import->getTableColumns($data['table']);
    
        $skip = ['id','deleted_at','created_at','updated_at','lead_source_id','pr_status'];
        return response()->view('imports.mapfields', compact('columns', 'fields', 'data', 'company_id', 'skip', 'title', 'requiredFields'));
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

        $this->validateInput($request);
        $this->import->setFields($data);
       
        if ($this->import->import()) {
            $this->_postimport();
        
            return redirect()->route('leadsource.index')->with('success', 'Leads imported');
        }
    }
    /**
     * [postimport description]
     * 
     * @return [type] [description]
     */
    private function _postimport()
    {
        
        $this->_copyAddresses();

        $this->_copyAddressIdtoImport();

        $this->_copyLeads();

        $this->_copyLeadContacts();
        
        $this->_setAddressImportIdToNull();

        $this->_validateStateCodes();

        $this->_truncateTable();

        // set import_id to null in addresses table
        return true;
    }
  
    /**
     * [_copyAddresses description]
     * 
     * @return [type] [description]
     */
    private function _copyAddresses()
    {
             $query = "insert ignore into addresses (" . implode(",", $this->addressfields) .",lead_import_id) select t.". implode(",t.", $this->addressfields). ",t.id as lead_import_id FROM `leadimport` t";
        
        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }
    /**
     * [_copyAddressIdtoImport description]
     * 
     * @return [type] [description]
     */
    private function _copyAddressIdtoImport()
    {
        $query ="update leadimport,addresses set leadimport.address_id = addresses.id where leadimpport.id = addresses.import_id";

        if (\DB::select(\DB::raw($query))) {
              return true;
        }
    }
    /**
     * [_copyLeads Copy incremntal data depending on type]
     * 
     * @return [type] [description]
     */
    private function _copyLeads()
    {
        if (count($this->leadfields)>0) {
             $query = "insert ignore into leads (" . implode(",", $this->leadfields) .",address_id) select t.". implode(",t.", $this->leadfields). ",t.address_id as address_id FROM `leadimport` t";
            
            
            if (\DB::select(\DB::raw($query))) {
                return true;
            }
        }
    }
    /**
     * [_copyLeadContacts description]
     * 
     * @return [type] [description]
     */
    private function _copyLeadContacts()
    {
        $query = "insert ignore into contacts 
        (address_id,firstname,lastname,title,email,phone,created_at)
            select addresses.id,firstname,lastname,title,email,leadimport.phone,addresess.created_at 
            FROM `leadimport`, leads where leadimport.id = address.lead_import_id";

        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }
    /**
     * [_validateStateCodes description]
     * 
     * @return [type] [description]
     */
    private function _validateStateCodes()
    {

        
        $query = "update leadimport, states set leadimport.state = states.statecode where leadimport.state = states.fullstate";
       
        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }
    /**
     * [SetAddressImportIdToNull description]
     *
     * @return [type] [description]
     */
    private function _setAddressImportIdToNull()
    {
        $query= "update addresses set import_id = null";
        return \DB::statement($query);
    }
    /**
     * [_truncateTable description]
     * 
     * @return [type] [description]
     */
    private function _truncateTable()
    {

        return \DB::statement("TRUNCATE TABLE `leadimport`");
    }
}
