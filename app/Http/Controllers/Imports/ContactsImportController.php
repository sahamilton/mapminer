<?php

namespace App\Http\Controllers\Imports;

use App\Contact;
use App\Company;
use App\ContactImport;
use App\LeadSource;
use App\Serviceline;
use App\Address;
use App\Jobs\ImportContactsJob;
use Illuminate\Http\Request;

class ContactsImportController extends ImportController
{

    public $sources;
    public $import;

    public function __construct(ContactImport $import)
    {
        
        $this->import = $import;
    }
    

    public function getFile(Request $request)
    {
        $requiredFields = $this->import->requiredFields;
        $servicelines = Serviceline::pluck('ServiceLine', 'id');
        $leadsources = LeadSource::active()
            ->orderBy('source')
            ->pluck('source', 'id')
            ->toarray();
        return response()->view('contacts.import', compact('requiredFields', 'leadsources', 'servicelines'));
    }

    public function import(Request $request)
    {
        
        $data = $this->uploadfile(request()->file('upload'));
        $data = array_merge($data, request()->all());

        $data['table'] = 'contacts_import';
        $data['route'] = 'contacts.mapfields';
        $data['additionaldata'] = [];
        $data['type'] = 'contacts';
        $fields = $this->getFileFields($data);

        $columns = $this->import->getTableColumns('contacts_import');

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
            $this->_matchAddresses();

            return $this->postImport();
        }

        
    }
    public function postImport()
    {
        $this->_setCompanyIdToNull();
        return response()->view('imports.contacts.index');
    }


    public function createLeads()
    {
        $contacts = $this->import->query()
            ->distinct('street', 'city', 'state', 'zip')
            ->select('company_id', 'businessname', 'street', 'city', 'state', 'zip', 'position', 'user_id', 'lead_source_id')
            ->whereNotNull('company_id')
            ->whereNull('address_id')
            ->get()
            ->toArray();
          
        foreach ($contacts as $contact) {
            $addresses[] = array_merge($contact, ['created_at'=>now()]);
        }
        
        Address::insert($addresses);
        $this->_matchAddresses(); 
        return redirect()->route('contacts.postimport')->withSuccess("Created " . count($contacts). " new leads");


    }

    public function importContacts()
    {
        

        ImportContactsJob::dispatch(auth()->user());
        $contacts = $this->import->query()
            ->whereNotNull('address_id')
            ->count();
        //Contact::delete($contacts);*/
        return redirect()->route('contacts.postimport')->withSuccess($contacts . " contacts imported");
    }


    public function createMissingCompanies()
    {
        $missingcompanies = $this->import->whereNull('company_id')
            ->distinct('businessname')
            ->pluck('businessname');

        $companies = Company::orderBy('companyname')
            ->pluck('companyname', 'id')
            ->toArray();
        return response()->view('imports.contacts.missingcompanies', compact('companies', 'missingcompanies'));

    }

    private function _setCompanyIdToNull()
    {
        $query = "update contacts_import set company_id = null where company_id = 0";
        return $this->_executeQuery($query);
    }
    /**
     * [_matchAddresses description]
     * 
     * @return [type] [description]
     */
    private function _matchAddresses()
    {
  
        $query = "update contacts_import t1
            join(
                select
                    a.id as contact, b.id as address_id
                FROM
                    contacts_import a

                INNER JOIN addresses b ON
                    b.id =(
                    SELECT
                        b1.id
                    FROM
                        addresses b1
                    WHERE
                        ST_DISTANCE_SPHERE(a.position, b1.position, 250) < 1
                        and a.company_id = b1.company_id 
                    ORDER BY
                        ST_DISTANCE_SPHERE(a.position, b1.position)
                    LIMIT 1
                )
                WHERE
                    a.company_id = b.company_id
                    and a.address_id is null) t2
                    set t1.address_id = t2.address_id
                    where t1.id = t2.contact";

        return $this->_executeQuery($query);
    }

    private function _executeQuery($query)
    {
        try {
            return \DB::statement($query);
        } catch (Exception $e) {
            throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }

    
}
