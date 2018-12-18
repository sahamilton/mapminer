<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Customer;
use App\CustomerImport;


class CustomerImportController extends ImportController
{
    public $project;
    public $sources;
    public $import;
    

    

    public function __construct(Customer $customer, CustomerImport $import){
        $this->customer = $customer;
        $this->import = $import;
        
    }

    public function getFile(Request $request){
        $requiredFields = $this->import->requiredFields;
       
        return response()->view('customers.import',compact ('requiredFields'));
    }


    public function import(Request $request) {
      

        $data = $this->uploadfile(request()->file('upload'));
        $data['table'] = 'customers';
        $data['route'] = 'customers.mapfields';
        $data['additionaldata'] = [];
        $data['type']=null;
        $fields = $this->getFileFields($data);

        $columns = $this->import->getTableColumns('customers'); 

        $skip = ['id','created_at','updated_at'];
        $requiredFields = $this->import->requiredFields;

        return response()->view('imports.mapfields',compact('columns','fields','data','skip','requiredFields'));
    }
    
    public function mapfields(Request $request){
        
        $data = $this->getData($request);  

        if($multiple = $this->import->detectDuplicateSelections(request('fields'))){
            return redirect()->route('customers.importfile')->withError(['You have mapped a field more than once.  Field: '. implode(' , ',$multiple)]);
        }
        if($missing = $this->import->validateImport(request('fields'))){

             
            return redirect()->route('customers.importfile')->withError(['You have to map all required fields.  Missing: '. implode(' , ',$missing)]);
       }
        $this->import->setFields($data);


        if($this->import->import()) {

            //map to see if any are already in existance
            //then copy new ones to addresses.
            // copy $$ to customerorders period
           return redirect()->route('customer.index');


        }
        
    }
    
    
 
    
}