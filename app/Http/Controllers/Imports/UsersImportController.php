<?php

namespace App\Http\Controllers\Imports;

use App\Company;
use App\Http\Requests\UsersImportFormRequest;
use App\Person;
use App\Serviceline;
use App\User;
use App\UserImport;
use Illuminate\Http\Request;

class UsersImportController extends ImportController
{
    public $company;
    public $person;
    public $user;
    public $import;
    public $userfields = [];
    public $personfields = [];

    /**
     * [__construct description].
     *
     * @param Person     $person [description]
     * @param User       $user   [description]
     * @param UserImport $import [description]
     */
    public function __construct(Person $person, User $user, UserImport $import)
    {
        $this->person = $person;
        $this->user = $user;
        $this->import = $import;
    }

    /**
     * [index description].
     *
     * @return [type] [description]
     */
    public function index()
    {
        $newPeople = $this->import->whereNull('person_id')
            ->orWhereNull('user_id')
            ->get();

        $roles = \App\Role::all();
        if (! $newPeople->count()) {
            return redirect()->route('importcleanse.flush');
        }

        return response()->view('admin.users.import.new', compact('newPeople', 'roles'));
    }

    /**
     * [getFile description].
     *
     * @return [type] [description]
     */
    public function getFile()
    {
        if ($this->import->count() > 0) {
            return redirect()->route('importcleanse.index');
        } else {
            $requiredFields = $this->import->requiredFields;
            //$servicelines = Serviceline::pluck('ServiceLine','id');

            return response()->view('admin.users.import', compact('requiredFields'));
        }
    }

    /**
     * [import description].
     *
     * @param UsersImportFormRequest $request [description]
     *
     * @return [type]                          [description]
     */
    public function import(UsersImportFormRequest $request)
    {
        $this->import->truncate();
        $data = $this->uploadfile(request()->file('upload'));

        $data['table'] = 'usersimport';

        $data['type'] = request('type');

        $data['route'] = 'users.mapfields';
        $fields = $this->getFileFields($data);

        $data['additionaldata'] = []; //['serviceline'=>implode(",",request('serviceline'))];
        $_addColumns = ['branches', 'role_id', 'mgr_emp_id', 'manager', 'reports_to', 'industry', 'address', 'city', 'state', 'zip', 'serviceline', 'hiredate', 'business_title', 'fullname'];
        $addColumn = $this->_addColumns($_addColumns);

        $columns = array_merge($this->import->getTableColumns('users'), $this->import->getTableColumns('persons'), $addColumn);

        $requiredFields = $this->import->requiredFields;
        $skip = ['id', 'password', 'confirmation_code', 'remember_token', 'created_at', 'updated_at', 'nonews', 'lastlogin', 'api_token', 'user_id', 'lft', 'rgt', 'depth', 'geostatus'];

        return response()->view('imports.mapfields', compact('columns', 'fields', 'data', 'skip', 'requiredFields'));
    }

    /**
     * [mapfields description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function mapfields(Request $request)
    {
        $data = $this->getData(request()->all());
        $data['table'] = 'usersimport';
        $this->import->setFields($data);

        if ($multiple = $this->import->detectDuplicateSelections(request('fields'))) {
            return redirect()->route('users.importfile')->withError(['You have to mapped a field more than once.  Field: '.implode(' , ', $multiple)]);
        }

        if ($missing = $this->import->validateImport(request('fields'))) {
            return redirect()->route('users.importfile')->withError(['You have to map all required fields.  Missing: '.implode(' , ', $missing)]);
        }

        if ($this->import->import()) {
            $this->import->postImport();

            return redirect()->route('importcleanse.index');
        } else {
            dd('whoops');
        }
    }

    /**
     * [fixUserErrors description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    public function fixUserErrors(Request $request)
    {
        switch (request('type')) {
        case 'branch':
            $this->_fixBranchErrors($request);
            break;

        case 'email':
            $this->_fixEmailErrors($request);
            break;

        default:

            break;
        }

        return redirect()->route('importcleanse.index');
    }

    /**
     * [_fixBranchErrors description].
     *
     * @param [type] $request [description]
     *
     * @return [type]          [description]
     */
    private function _fixBranchErrors($request)
    {
        $this->import->whereIn('employee_id', array_keys(request('ignore')))->update(['branches' => null]);
        // update all branches
        $toUpdate = array_diff_key(request('branch'), request('ignore'));
        $update = $this->import->whereIn('employee_id', $toUpdate)->get();
        foreach ($update as $upd) {
            $upd->branches = str_replace(' ', '', $toUpdate[$upd->employee_id]);
            $upd->save();
        }
    }

    /**
     * [_fixEmailErrors description].
     *
     * @return [type] [description]
     */
    private function _fixEmailErrors()
    {
        //$data['email'] = request('email');
        $imports = $this->import->whereIn('id', array_keys(request('import')))->with('user')->get();
        $data = request('import');
        foreach ($imports as $import) {
            if ($data[$import->id] == 'import') {
                $user = $import->user()->first();
                $user->employee_id = $import->employee_id;
                $user->save();
            } else {
                $import->employee_id = $import->user->employee_id;
                $import->save();
            }
        }
    }

    /**
     * [_addColumns description].
     *
     * @param [type] $columns [description]
     *
     * @return stdClass $addColumn [<description>]
     */
    private function _addColumns($columns)
    {
        foreach ($columns as $column) {
            $columns = new \stdClass;
            $columns->Field = $column;
            $addColumn[] = $columns;
        }

        return $addColumn;
    }
}
