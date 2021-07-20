<?php

namespace App\Http\Controllers\Imports;

use Illuminate\Http\Request;
use App\Branch;
use App\Address;
use App\LeadImport;
use App\LeadSource;

use App\Http\Requests\LeadImportFormRequest;

class AddressImportController extends ImportController
{
    public $lead;
    public $leadsources;
    public $import;
    public $importtable = 'addressimports';
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
            'created_at',
            'branch_id'
        ];


    // $extrafields = ['leads']['description'];


    public $contactfields =[
                'address_id',
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
        $this->address = $address;
        $this->import = $import;
        $this->leadsources = $leadsource;
    }
    /**
     * [getFile description]
     * 
     * @param  Request $request [description]
     * 
     * @param  [type]  $id      [description]
     * @param  [type]  $type    [description]
     * @return [type]           [description]
     */
    public function getFile(Request $request, $id = null, $type = null)
    {

        $sources = $this->leadsources->all()->pluck('source', 'id');

        if ($leadsource->count() == 0) {
            return redirect()->route('leadsource.index')->with('error', 'You must create a lead source first');
        }
        if ($id) {
            $leadsource = $this->leadsources->find($id);
        }
        $requiredFields = $this->address->requiredfields;
        if ($type == 'assigned') {
            $requiredFields[] = 'employeee_number';
        }
        dd($requiredFields);
        return response()->view('leads.import', compact('sources', 'leadsource', 'requiredFields', 'type'));
    }

    /**
     * [import description]
     * 
     * @param LeadImportFormRequest $request [description]
     * 
     * @return [type]                         [description]
     */
    public function import(LeadImportFormRequest $request)
    {
  

        $data = $this->uploadfile(request()->file('upload'));
        $title="Map the leads import file fields";
        $requiredFields = $this->import->requiredFields;

        $data['type']=request('type');

        if ($data['type']== 'assigned') {
            $data['table']='leadimport';
            $requiredFields[]='employee_id';
        } else {
            $data['table']='leadimport';
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
            $this->_postimport($request);
        
            return redirect()->route('leadsource.index')->with('success', 'Leads imported');
        }
    }
    /**
     * [_postimport description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _postimport(Request $request)
    {
        /*
        // get same, added & deleted based on spatial
        $data = $this->getAddEditDeleteOnSpatial()


        $this->copyAddresses();

        $this->copyAddressIdtoImport();

        $this->copyLeads();
       
        if (request()->filled('contacts')) {
            $this->copyLeadContacts();
        }
            
        //$this->updateLeadPivot();
        //$this->setAddressImportIdToNull();

        
            


        $this->truncateTable();
        
       
       select addresses.id, addresses.businessname,addresses.city, addresses_import.id,addresses.businessname, addresses.city, ST_Distance_Sphere(addresses.position,addresses_import.position) as distance from addresses_import,addresses
        where addresses.company_id = addresses_import.company_id
        and ST_Distance_Sphere(addresses.position,addresses_import.position) < 100

        SELECT addresses.id, addresses.businessname,addresses.street, addresses.city,addresses.zip FROM `addresses` left join addresses_import on ST_Distance_Sphere(addresses.position,addresses_import.position) < 100 where addresses.company_id = 275 and addresses_import.id is null



        // set import_id to null in addresses table
        return true;*/
    }
    /**
     * [_addAssignedPID description]
     * 
     * @return something
     */
    private function _addAssignedPID() 
    {
        $query ="UPDATE leadimport dest, (SELECT leadimport.id as id, persons.id as pid from persons,leadimport,users where REPLACE(leadimport.employee_id, '\r', '')=users.employee_id and persons.user_id = users.id) src set dest.pid = src.pid where dest.id = src.id";
        if (\DB::select(\DB::raw($query))) {
           
            return true;
        }
    }
    

    private function copyAddresses()
    {
             $query = "insert ignore into addresses (" . implode(",", $this->addressfields) .",lead_import_id) select t.". implode(",t.", $this->addressfields). ",t.id as lead_import_id FROM `leadimport` t";
        
        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }

    private function copyAddressIdtoImport()
    {
        $query ="update leadimport,addresses set leadimport.address_id = addresses.id where leadimpport.id = addresses.import_id";

        if (\DB::select(\DB::raw($query))) {
              return true;
        }
    }
    /*
    Copy incremntal data depending on type

    */
    private function copyLeads()
    {
        if (count($this->leadfields)>0) {
             $query = "insert ignore into leads (" . implode(",", $this->leadfields) .",address_id) select t.". implode(",t.", $this->leadfields). ",t.address_id as address_id FROM `leadimport` t";
            
            
            if (\DB::select(\DB::raw($query))) {
                return true;
            }
        }
    }

    private function copyLeadContacts()
    {
        $query = "insert ignore into contacts 
        (address_id,firstname,lastname,title,email,phone,created_at)
            select addresses.id,firstname,lastname,title,email,leadimport.phone,addresess.created_at 
            FROM `leadimport`, leads where leadimport.id = address.lead_import_id";

        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }
    /*
    private function updateLeadPivot() {
        $query ="insert ignore into lead_person_status (related_id,person_id,status_id,type)
                SELECT distinct leads.id, leadimport.pid ,'2','prospect'  from leads,leadimport
                where MD5(lower(replace(concat(`leads`.`companyname`,`leads`.`businessname`,`leads`.`address`,`leads`.`city`,`leads`.`state`,`leads`.`zip`),' ',''))) = MD5(lower(replace(concat(`leadimport`.`companyname`,`leadimport`.`businessname`,`leadimport`.`address`,`leadimport`.`city`,`leadimport`.`state`,`leadimport`.`zip`),' ','')))
                and leads.lead_source_id = leadimport.lead_source_id;";
       if (\DB::select(\DB::raw($query))) {
           
            return true;
        }
    }
    */

    private function setAddressImportIdToNull()
    {
        $query= "update addresses set import_id = null";
        return \DB::statement($query);
    }

    private function truncateTable()
    {

        return \DB::statement("TRUNCATE TABLE `leadimport`");
    }
}
