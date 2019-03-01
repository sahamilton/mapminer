<?php

namespace App\Http\Controllers;

use App\UserImport;
use App\Person;
use App\User;
use Illuminate\Http\Request;

class UserImportCleanseController extends Controller
{
    public $import;
    public $user;
    public $person;

    public function __construct(User $user, UserImport $import, Person $person)
    {
        $this->import = $import;
        $this->user = $user;
        $this->person = $person;
    }

    public function index()
    {
        // show users to delete
        

        if ($data = $this->import->handleUserErrors()) {
            return response()->view('admin.users.import.errors', compact('data'));
        } else {
                $data['deleteUsers'] = $this->import->getUsersToDelete();
                $data['newUsers'] = $this->import->getUsersToCreate();
                $data['noManagers'] = $this->getMissingManagers();
        
                return response()->view('admin.users.import.index', compact('data'));
        }
    }
    public function getMissingManagers()
    {
        $missingmanagers = $this->import->whereNull('reports_to')->get();
        //dd($missingmanagers);
        foreach ($missingmanagers as $missing) {
            if ($mgr = $this->import->where('employee_id', '=', $missing->mgr_emp_id)->first()) {
                $missing->reports_to = $mgr->person_id;
                $missing->save();
            }
        }
        
        $mgr_id = $this->import->whereNull('reports_to')
                    ->select('mgr_emp_id')
                    ->distinct('mgr_emp_id')->get()->toArray();
        return $this->import->whereIn('employee_id', $mgr_id)->get();
    }

    public function importAllUsers()
    {
        $this->import->updateExistingUsers();
        $this->person->rebuild();
        return redirect()->route('importcleanse.index');
    }
    public function createNewUsers(Request $request)
    {
        // we need to chunk this //

        $import = $this->import->whereIn('id', request('insert'))->chunk(25, function ($users) {
            foreach ($users as $import) {
                $newuser = $this->user->create($import->toArray());
                $newuser->roles()->sync([$import->role_id]);
                $import->user_id=$newuser->id;
                $person = $this->person->create($import->toArray());
                $import->person_id = $person->id;
                $import->save();
            }
        });
        return redirect()->route('importcleanse.index')->withMessage("All created");
    }



    public function bulkdestroy(Request $request)
    {
        
        $delete = request('delete');
        // dont want to commint suicde!
        if (($key = array_search(auth()->user()->id, $delete)) !== false) {
            unset($delete[$key]);
        }

        $this->user->destroy($delete);
        return redirect()->route('importcleanse.index')->withMessage("Deleted");
    }

    public function flush()
    {
       
        \DB::table($this->import->table)->truncate();
        return redirect()->route('users.importfile');
    }
}
