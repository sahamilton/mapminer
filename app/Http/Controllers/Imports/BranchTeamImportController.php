<?php

namespace App\Http\Controllers\Imports;

use App\Models\Branch;
use App\Models\BranchTeamImport;
use App\Http\Requests\BranchTeamImportFormRequest;
use App\Models\Person;
use Illuminate\Http\Request;

class BranchTeamImportController extends ImportController
{
    public $branch;
    public $person;
    public $requiredFields = ['branch_id', 'role_id', 'person_id'];
    public $branchteamfields = ['branch_id', 'role_id', 'person_id'];
    public $importtable = 'branchteamimport';

    public function __construct(Branch $branch, Person $person, BranchTeamImport $import)
    {
        $this->branch = $branch;
        $this->person = $person;
        $this->import = $import;
    }

    public function getFile()
    {
        return response()->view('branches.teamimport');
    }

    public function import(BranchTeamImportFormRequest $request)
    {
        $title = 'Map the branch team import file fields';

        $data = $this->uploadfile(request()->file('upload'));
        $requiredFields = $this->import->requiredFields;
        $data['table'] = $this->import->table;
        $data['type'] = 'branchteamimport';
        $data['additionaldata'] = [];
        $data['route'] = 'branchteam.mapfields';
        $fields = $this->getFileFields($data);
        $columns = $this->branch->getTableColumns($data['table']);
        $skip = ['created_at', 'updated_at'];

        return response()->view('imports.mapfields', compact('columns', 'fields', 'data', 'skip', 'title', 'requiredFields'));
    }

    public function mapfields(Request $request)
    {
        $data = $this->getData($request);
        $this->validateInput($request);
        $this->import->setFields($data);
        if ($this->import->import()) {
            $importErrors = $this->import->checkForErrors();

            if (count($importErrors['missingPeople']) > 0) {
                $people = $this->person->personroles([3, 5, 9]);

                return response()->view('branches.missingbranchpeople', compact('people', 'importErrors'));
            } elseif (count($importErrors['missingBranches']) > 0) {
                $branches = $this->branch->orderBy('id')->pluck('branchname', 'id');

                return response()->view('branches.missingbranchteam', compact('branches', 'importErrors'));
            } elseif (count($importErrors['missingRoles']) > 0) {
                dd('some invalid roles there');
                $branches = $this->branch->orderBy('id')->pluck('branchname', 'id');

                return response()->view('branches.missingbranchteam', compact('branches', 'importErrors'));
            } else {
                $this->refreshteam();
                $this->import->truncateImport($this->importtable);

                return redirect()->route('branches.index')->with('success', 'Branch teams updated');
            }
        }
    }

    private function refreshteam()
    {
        $query = 'insert ignore into branch_person ('.implode(',', $this->branchteamfields).') select t.'.implode(',t.', $this->branchteamfields).' FROM `branchteamimport` t';
        if (\DB::select(\DB::raw($query))) {
            return true;
        }
    }
}
