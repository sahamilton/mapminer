<?php

namespace App\Http\Controllers\Imports;

use App\Http\Requests\ProjectsImportFormRequest;
use App\Models\Project;
use App\Models\ProjectCompanyImport;
use App\Models\ProjectSource;
use Illuminate\Http\Request;

class ProjectsCompanyImportController extends ImportController
{
    public $project;
    public $sources;
    public $import;

    public $projectcompanyfields = ['id', 'firm', 'addr1', 'addr2', 'city', 'state', 'zip', 'county', 'phone'];
    public $projectcompanyimportfields = ['company_id', 'firm', 'addr1', 'addr2', 'city', 'state', 'zip', 'county', 'phone'];
    public $projectcontactfields = ['contact', 'title', 'company_id', 'contactphone', 'contactemail'];
    public $projectcontactimportfields = ['contact', 'title', 'company_id', 'contactphone', 'contactemail'];

    public function __construct(Project $project, ProjectSource $source, ProjectCompanyImport $import)
    {
        $this->project = $project;
        $this->sources = $source;
        $this->import = $import;
    }

    public function getFile(Request $request)
    {
        $requiredFields = $this->import->requiredFields;
        $source = request('source');
        $sources = $this->sources->all()->pluck('source', 'id');
        $tables = ['projectcompanies', 'projectcontacts'];

        return response()->view('projects.importcompany', compact('sources', 'source', 'tables', 'requiredFields'));
    }

    public function import(ProjectsImportFormRequest $request)
    {
        $data = $this->uploadfile(request()->file('upload'));
        $data['table'] = 'projectcompanyimport';
        $data['additionaldata'] = [];
        $skip = ['created_at', 'updated_at', 'project_source_id', 'company_id', 'pr_status', 'serviceline_id'];
        $requiredFields = $this->import->requiredFields;
        $data['type'] = request('type');
        $data['route'] = 'projectcompany.mapfields';
        $fields = $this->getFileFields($data);

        $columns = $this->project->getTableColumns($data['table']);

        return response()->view('imports.mapfields', compact('columns', 'fields', 'data', 'skip', 'requiredFields'));
    }

    public function mapfields(Request $request)
    {
        $data = $this->getData($request);

        if ($multiple = $this->import->detectDuplicateSelections(request('fields'))) {
            return redirect()->route('projects.importfile')->withError(['You have to mapped a field more than once.  Field: '.implode(' , ', $multiple)]);
        }
        if ($missing = $this->import->validateImport(request('fields'))) {
            return redirect()->route('projects.importfile')->withError(['You have to map all required fields.  Missing: '.implode(' , ', $missing)]);
        }
        $this->import->setFields($data);

        if ($this->import->import()) {
            return $this->postImport($data);
        }
    }

    private function postImport($data)
    {
        $this->createCompanyId();
        $this->cleanseContacts();
        $this->updateContacts();
        $this->createContactId();
        $this->copyProjectCompanies();
        $this->copyProjectContacts();
        $this->updatePivotTable();

        return redirect()->route('projectsource.index')->with('success', 'Projects, Companies & Contacts Linked');
    }

    private function createCompanyId()
    {
        $query = "Update projectcompanyimport set company_id = md5(lcase(trim(replace(concat(firm,addr1,city,state,zip),' ',''))))";
        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }

    private function createContactId()
    {
        $query = "Update projectcompanyimport set contact_id = md5(lcase(trim(replace(concat(contact,company_id),' ',''))))";
        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }

    private function copyProjects()
    {
        $query = 'insert ignore into projects ('.implode(',', $this->projectfields).') select t.'.implode(',t.', $this->projectfields).' FROM `projectsimport` t';
        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }

    /*
    remove contacts where should be null
    */
    private function cleanseContacts()
    {
        $fields = ['firstname', 'lastname', 'contact'];
        foreach ($fields as $field) {
            $query = 'update projectcompanyimport set '.$field.'= null where '.$field." ='';";
            \DB::select(\DB::raw($query));
        }

        return true;
    }

    /*
    Set contact to equal firstname + lastname
    */

    private function updateContacts()
    {
        // update projectcompanyimport set contact= null, firstname = null, lastname = null where firstname ='' and lastname='' and contact ='';
        $query = "update projectcompanyimport set contact = concat(firstname,' ',lastname) where contact is null";
        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }

    private function copyProjectContacts()
    {
        $query = 'insert ignore into projectcontacts ('.implode(',', $this->projectcontactfields).') select distinct t.'.implode(',t.', $this->projectcontactimportfields).' FROM `projectcompanyimport` t where t.contact is not null';

        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }

    private function updatePivotTable()
    {
        $query = 'Insert ignore into project_company_contact (project_id,company_id,type,contact_id) 
    Select project_id,company_id,type,contact_id from projectcompanyimport;';

        if (\DB::select(\DB::raw($query))) {
            return \DB::statement('TRUNCATE TABLE `projectsimport`');
        }
    }

    public function copyProjectCompanies()
    {
        $query = 'insert ignore into projectcompanies ('.implode(',', $this->projectcompanyfields).') select distinct t.'.implode(',t.', $this->projectcompanyimportfields).' FROM `projectcompanyimport` t
            ';

        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }
}
