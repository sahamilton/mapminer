<?php

namespace App\Models;

use App\Jobs\associateBranches;
use App\Jobs\associateIndustries;
use App\Jobs\ProcessGeoCode;
use App\Jobs\ProcessPersonRebuild;
use App\Jobs\ProcessUserImport;
use App\Jobs\updateUserRoles;
use App\Jobs\updateUserServiceLines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserImport extends Imports
{
    public $uniqueFields = ['employee_id'];
    public $table = 'usersimport';
    public $requiredFields = ['employee_id', 'firstname', 'lastname', 'role_id', 'email'];
    public $user;
    public $person;

    /**
     * [checkUniqueFields description].
     *
     * @return [type] [description]
     */
    public function checkUniqueFields()
    {
        foreach ($this->uniqueFields as $field) {
            return $importerrors = $this->checkFields($field);
        }

        return false;
    }

    /**
     * [getDataErrors description].
     *
     * @return [type] [description]
     */
    public function getDataErrors()
    {
        $errors['branch'] = $this->_checkBranches();
        $errors['emails'] = $this->_checkEmails();
        if (! $this->array_empty($errors)) {
            return $errors;
        } else {
            return false;
        }
    }

    /**
     * [_checkBranches description].
     *
     * @return [type] [description]
     */
    private function _checkBranches()
    {
        $data = [];

        $branchesImported = $this->whereNotNull('branches')->pluck('branches', 'employee_id');
        $branches = Branch::pluck('id')->toArray();
        foreach ($branchesImported as $empid => $branchstring) {
            $branchImport = explode(',', str_replace(' ', '', $branchstring));
            foreach ($branchImport as $checkId) {
                if (! in_array($checkId, $branches)) {
                    $data[$empid][] = $checkId;
                }
            }
        }
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * [_checkEmails description].
     *
     * @return [type] [description]
     */
    private function _checkEmails()
    {
        $emails = \DB::select(\DB::raw('SELECT users.email as useremail,users.employee_id as userempid,usersimport.* FROM `usersimport`,`users` where `usersimport`.`email` = `users`.`email` and `usersimport`.`employee_id` != `users`.`employee_id`'));
        if (count($emails) > 0) {
            return $emails;
        }

        return false;
    }

    /**
     * [checkFields description].
     *
     * @param [type] $field [description]
     *
     * @return [type]        [description]
     */
    private function checkFields($field)
    {
        $query = 'SELECT '.$this->table.'.'.$field.' from '.$this->table.' 
			left join users on '.$this->table.'.'.$field.' = users.'.$field.'
			where users.'.$field.' is not null';
        if ($result = \DB::select(\DB::raw($query))) {
            $errors = $this->getImportErrors($field, $result);
            $errorfield = new \stdClass;
            $errorfield->Field = $field;
            $errors[] = $errorfield;

            return $errors;
        } else {
            return false;
        }
    }

    /**
     * [invalidEmpId description].
     *
     * @return [type] [description]
     */
    public function invalidEmpId()
    {
    }

    /**
     * [getImportErrors description].
     *
     * @param [type] $field  [description]
     * @param [type] $result [description]
     *
     * @return [type]         [description]
     */
    public function getImportErrors($field, $result)
    {
        foreach ($result as $error) {
            $items[] = $error->$field;
        }

        return \DB::select(\DB::raw('select * from '.$this->table.' where '.$field." in ('".implode("','", $items)."')"));
    }

    /**
     * [postImport description].
     *
     * @return [type] [description]
     */
    public function postImport()
    {
        // clean up null values in import db

        $this->_cleanseImport();
        $this->_setUserId();
        $this->_setPersonId();

        return $this->_setManagersId();
    }

    /**
     * [_setUserId description].
     *
     * @return bookean [<description>]
     */
    private function _setUserId()
    {
        $queries = ['update usersimport,users
				set usersimport.user_id = users.id
				where usersimport.employee_id = users.employee_id'];

        return $this->_executeImportQueries($queries);
    }

    /**
     * [_setPersonId description].
     *
     * @return bool
     */
    private function _setPersonId()
    {
        $queries = ['update usersimport,users, persons
				set usersimport.person_id = persons.id
				where usersimport.employee_id = users.employee_id
				and users.id = persons.user_id'];

        return $this->_executeImportQueries($queries);
    }

    /**
     * [_setManagersId description].
     *
     * @return [<description>]
     */
    private function _setManagersId()
    {
        $queries = ["update usersimport,users,persons
				set usersimport.reports_to = persons.id , 
                usersimport.manager = concat_ws(' ', persons.firstname, persons.lastname)
				where usersimport.mgr_emp_id = users.employee_id
				and users.id = persons.user_id"];

        return $this->_executeImportQueries($queries);
    }

    /**
     * [_cleanseImport description].
     *
     * @return [type] [description]
     */
    private function _cleanseImport()
    {
        $fields = ['reports_to', 'branches', 'address', 'city', 'state', 'zip', 'industry', 'mgr_emp_id'];
        foreach ($fields as $field) {
            if ($field == 'reports_to') {
                $queries[] = 'update usersimport set '.$field.' = null where '.$field.' = 0';
            }
            if ($field == 'mgr_emp_id') {
                $queries[] = 'update usersimport set mgr_emp_id = left(mgr_emp_id,6) where char_length(mgr_emp_id)=7';
            }

            $queries[] = 'update usersimport set '.$field.' = null where '.$field." = ''";
        }

        return $this->_executeImportQueries($queries);
    }

    /**
     * [getUsersToDelete description].
     *
     * @return [type] [description]
     */
    public function getUsersToDelete()
    {
        return User::leftJoin(
            'usersimport', function ($join) {
                $join->on('users.employee_id', '=', 'usersimport.employee_id');
            }
        )
        ->with('person', 'roles')
        ->whereNull('usersimport.employee_id')
        ->select('users.*')
        ->get();
    }

    /**
     * [getUsersToCreate description].
     *
     * @return [type] [description]
     */
    public function getUsersToCreate()
    {
        return $this->leftJoin(
            'users', function ($join) {
                $join->on('usersimport.employee_id', '=', 'users.employee_id');
            }
        )

        ->whereNull('users.employee_id')
        ->with('role', 'manager')
        ->select('usersimport.*')
        ->get();
    }

    /**
     * [_executeImportQueries description].
     *
     * @param [type] $queries [description]
     *
     * @return [type]          [description]
     */
    private function _executeImportQueries($queries)
    {
        foreach ($queries as $query) {
            if ($result = \DB::select(\DB::raw($query))) {
                return true;
            }
        }
    }

    /**
     * [role description].
     *
     * @return [type] [description]
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * [manager description].
     *
     * @return [type] [description]
     */
    public function manager()
    {
        return $this->belongsTo(Person::class, 'reports_to', 'id');
    }

    /**
     * [user description].
     *
     * @return [type] [description]
     */
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    /**
     * [updateExistingUsers description].
     *
     * @return [type] [description]
     */
    public function updateExistingUsers()
    {
        $existing = $this->whereNotNull('user_id')
            ->whereNotNull('person_id')
            ->whereNotNull('reports_to')
            ->where('imported', '=', 0)
            ->chunk(
                100, function ($users) {
                    $this->_updateImportRecords($users);
                }
            );
    }

    /**
     * [_updateImportRecords description].
     *
     * @param [type] $users [description]
     *
     * @return [type]        [description]
     */
    private function _updateImportRecords($users)
    {
        foreach ($users as $userimport) {
            ProcessUserImport::dispatch($userimport);
        }
    }

    /**
     * [handleUserErrors description].
     *
     * @return [type] [description]
     */
    public function handleUserErrors()
    {
        dd($this->import->getDataErrors());
        if ($data['errors'] = $this->import->getDataErrors()) {
            $import = [];
            if ($brancherrors = $data['errors']['branch']) {
                $data['import'] = $this->import->whereIn('employee_id', array_keys($brancherrors))->get();
            } else {
                unset($data['errors']['branch']);
            }
            if (! $data['errors']['emails']) {
                unset($data['errors']['emails']);
            }

            return $data;
        }

        return false;
    }
}
