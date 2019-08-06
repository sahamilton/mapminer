<?php
namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;
use App\Person;
use App\Company;
use App\Permission;
use App\Http\Requests\UserFormRequest;
use App\Http\Requests\UserBulkImportForm;
use App\Branch;
use App\Track;
use Carbon\Carbon;
use App\Serviceline;
use Illuminate\Http\Request;
use App\SearchFilter;
use App\Http\Controllers\BaseController;

class AdminUsersController extends BaseController
{


    /**
     * User Model
     * 
     * @var User
     */
    public $user;

    /**
     * Role Model
     * 
     * @var Role
     */
    protected $role;

    /**
     * Person Model
     * 
     * @var Person
     */
    public $person;


    /**
     * Permission Model
     * 
     * @var Permission
     */
    protected $permission;

    /**
     * Servicelines array
     * 
     * @var userServiceLines
     */

    /**
     * [$branch description]
     * 
     * @var [type]
     */
    public $branch;
    /**
     * [$serviceline description]
     * 
     * @var [type]
     */
    public $serviceline;
    /**
     * [$searchfilter description]
     * 
     * @var [type]
     */
    public $searchfilter;


    /**
     * [__construct description]
     * 
     * @param User         $user         [description]
     * @param Role         $role         [description]
     * @param Person       $person       [description]
     * @param Permission   $permission   [description]
     * @param Branch       $branch       [description]
     * @param Track        $track        [description]
     * @param Serviceline  $serviceline  [description]
     * @param SearchFilter $searchfilter [description]
     */
    public function __construct(
        User $user, 
        Role $role, 
        Person $person, 
        Permission $permission, 
        Branch $branch, 
        Track $track, 
        Serviceline $serviceline, 
        SearchFilter $searchfilter
    ) {

        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
        $this->person = $person;
        $this->track = $track;
        $this->branch = $branch;
        $this->serviceline = $serviceline;
        $this->searchfilter = $searchfilter;
    }

    /**
     * [index description]
     * 
     * @param Serviceline|null $serviceline [description]
     * 
     * @return [type]                        [description]
     */
    public function index(Serviceline $serviceline = null)
    {


        if (! $serviceline) {
        
            $servicelines = $this->userServiceLines;
                $serviceline = 'All';
                $title = 'People / User Management';
        } else {
           
            
            $title = $serviceline->ServiceLine ." users";
        }
            
        $users = $this->user
            ->with('roles', 'usage', 'person', 'serviceline');
           
        /*if ($serviceline) {
            $users = $users->whereHas(
                'serviceline', function ($q) {
                    $q->whereIn('serviceline_id', $this->userServiceLines);
                }
            );
        }*/
           
          $users = $users->get();
         

        // Show the page
        return response()->view('admin.users.index', compact('users', 'title', 'serviceline'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // All roles
        
        $roles = $this->role->all();

        // Get all the available permissions
        $permissions = $this->permission->all();

        // Selected groups
        $selectedRoles = \Input::old('roles', []);

        // Selected permissions
        $selectedPermissions = \Input::old('permissions', []);

        // Title
        $title = 'Create New User';

        // Mode
        $mode = 'create';

        // Service lines

        $servicelines = $this->person->getUserServiceLines();
        // get all branches of this serviceline
  
        $branches =$this->branch->wherehas(
            'servicelines', function ($q) use ($servicelines) {
                $q->whereIn('servicelines.id', array_keys($servicelines));
            }
        )
        ->pluck('branchname', 'id')
        ->toArray();

        $branches[0] = 'none';
            ksort($branches);
        $verticals = $this->searchfilter->industrysegments();


        $managers = $this->_getManagerList();
        // Show the page

        return response()->view('admin.users.create', compact('roles', 'permissions', 'verticals', 'selectedRoles', 'selectedPermissions', 'title', 'mode', 'managers', 'servicelines', 'branches'));
    }

    /**
     * [store description]
     * 
     * @param UserFormRequest $request [description]
     * 
     * @return [type]                   [description]
     */
    public function store(UserFormRequest $request)
    {

        
        $user = $this->user->create(request()->all());
        $user->api_token = md5(uniqid(mt_rand(), true));
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $this->_updatePassword($request, $user);
        if (request()->filled('confirm')) {
            $user->confirmed = request('confirm');
        }

        if ($user->save()) {
            // need to get the lat lng;
            $name = request(['firstname','lastname','phone','business_title']);
            if (request()->filled('address')) {
                $geoCode = app('geocoder')->geocode(request('address'))->get();
                $person = $this->person->getGeoCode($geoCode);
            } else {
                $person['lat']=null;
                $person['lng']=null;
                $person['position'] = null;
            }
            $person = array_merge($person, $name);
            $user->person()->create($person);
            $person = $user->person;
            $person = $this->_updateAssociatedPerson($person, request()->all());
            $person = $this->_associateBranchesWithPerson($person, request()->all());
            $track=Track::create(['user_id'=>$user->id]);
            $user->saveRoles(request('roles'));
            $user->serviceline()->attach(request('serviceline'));
            $person->rebuild();
            return redirect()->route('person.details', $person->id)
                ->with('success', 'User created succesfully');
        } else {
            return redirect()->route('users.create')

                ->withInput(request()->except('password'))

                ->with('error', 'Unable to create user');
        }
    }


     /**
      * [show description]
      * 
      * @param User   $user [description]
      * 
      * @return [type]       [description]
      */
    public function show(User $user)
    {
        $user->load('person');

        return redirect()->route('person.details', $user->person->id);

    }
    /**
     * [edit description]
     * 
     * @param User   $user [description]
     * 
     * @return [type]       [description]
     */
    public function edit(User $user)
    {

        
        if ($user) {
            $user->load('serviceline', 'person', 'person.branchesServiced', 'person.industryfocus', 'roles');
            $roles = $this->role->all();
            $permissions = $this->permission->all();


            $title = 'Update user';

            $mode = 'edit';
            $managers = $this->_getManagerList();
            $branchesServiced = $user->person->branchesServiced()->pluck('branchname', 'id')->toArray();
           
            $branches = $this->_getUsersBranches($user, $branchesServiced);

            $verticals = $this->searchfilter->industrysegments();
            $servicelines = $this->person->getUserServiceLines();
         
            return response()->view('admin.users.edit', compact('user', 'roles', 'permissions', 'verticals', 'title', 'mode', 'managers', 'servicelines', 'branches', 'branchesServiced'));
        } else {
            return redirect()->to(route('users.index'))->with('error', 'User does not exist');
        }
    }
    
    /**
     * [update description]
     * 
     * @param UserFormRequest $request [description]
     * @param User            $user    [description]
     * 
     * @return [type]                   [description]
     */
    public function update(UserFormRequest $request, User $user)
    {
      
        $user->load('person');
        $oldUser = clone($user);

        $this->_updatePassword($request, $user);

        if ($user->update(request()->except('password'))) {

            $person = $this->_updateAssociatedPerson($user->person, request()->all());
            $person = $this->_associateBranchesWithPerson($person, request()->all());        
            $user->saveRoles(request('roles'));
            $this->_updateServicelines($request, $user);

            $this->_updateIndustryVertical($request, $person);
            return redirect()->to(route('users.index'))->with('success', 'User updated succesfully');
        } else {
            return redirect()->to('admin/users/' . $user->id . '/edit')
                ->with('error', 'Unable to update user');
        }
    }
    
    /**
     * [_updatePassword description]
     * 
     * @param UserFormRequest $request [description]
     * @param User            $user    [description]
     * 
     * @return [type]                   [description]
     */
    private function _updatePassword(UserFormRequest $request, User $user)
    {
        if (request()->filled('password')) {
            $user->password = \Hash::make(request('password'));
            $user->save();
        }
    }
    /**
     * [_updateServicelines description]
     * 
     * @param UserFormRequest $request [description]
     * @param User            $user    [description]
     * 
     * @return [type]                   [description]
     */
    private function _updateServicelines(UserFormRequest $request, User $user)
    {

        if (request()->filled('serviceline')) {


                $user->serviceline()->sync(request('serviceline'));

        }
    }
    /**
     * [_updateIndustryVertical description]
     * 
     * @param UserFormRequest $request [description]
     * @param Person          $person  [description]
     * 
     * @return [type]                   [description]
     */
    private function _updateIndustryVertical(UserFormRequest $request, Person $person)
    {
        if (request()->filled('vertical')) {
                $verticals = request('vertical');

            if ($verticals[0]==0) {
                $person->industryfocus()->sync([]);
            } else {
                $person->industryfocus()->sync(request('vertical'));
            }
        } else {
            $person->industryfocus()->sync([]);
        }
    }
    /**
     * [lastlogged description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function lastlogged(Request $request)
    {
       
        $lastlogged = Carbon::createFromFormat('m/d/Y', request('fromdatepicker'));
      
        $users = $this->user->where('lastlogin', '<=', $lastlogged)
            ->with('roles', 'person')
            ->get();
        return response()->view('admin.users.lastlogged', compact('users', 'lastlogged'));
    }
    /**
     * [_associateBranchesWithPerson description]
     * 
     * @param [type] $person [description]
     * @param [type] $data   [description]
     * 
     * @return [type]         [description]
     */
    private function _associateBranchesWithPerson($person, $data)
    {
  
        $syncData=[];
        if (isset($data['branchstring'])) {
            $data['branches'] = $this->branch->getBranchIdFromid($data['branchstring']);
        }

        if (isset($data['branches']) && count($data['branches'])>0 && $data['branches'][0]!=0) {
            foreach ($data['branches'] as $branch) {
                if ($data['roles']) {
                    foreach ($data['roles'] as $role) {
                        $syncData[$branch]=['role_id'=>$role];
                    }
                }
            }
        }
        
        $person->branchesServiced()->sync($syncData);

        return $person;
    }
    /**
     * [_updateAssociatedPerson description]
     * 
     * @param Person $person [description]
     * @param [type] $data   [description]
     * 
     * @return [type]         [description]
     */
    private function _updateAssociatedPerson(Person $person, $data)
    {
       
       
        $geodata = $person->updatePersonsAddress($data);
        
        $data = array_merge($data, $geodata);
        $person->update($data);

        if (isset($data['vertical'])&& $data['vertical'][0]!=0) {
            $person->industryfocus()->sync($data['vertical']);
        }


        return $person;
    }
    /**
     * [_getUsersBranches description]
     * 
     * @param User   $user             [description]
     * @param [type] $branchesServiced [description]
     * 
     * @return [type]                   [description]
     */
    private function _getUsersBranches(User $user, $branchesServiced = null)
    {

        $userServiceLines = $user->serviceline->pluck('id', 'serviceline')->toArray();
        if (isset($user->person->lat) && $user->person->lat !=0) {
            $branches = $this->branch->whereHas(
                'servicelines', function ($q) use ($userServiceLines) {
                    $q->whereIn('servicelines.id', $userServiceLines);
                }
            )
            ->nearby($user->person, 200)
            ->limit(20)
            ->pluck('branchname', 'id')->toArray();
        } else {
            $branches = $this->branch->whereHas(
                'servicelines', function ($q) use ($userServiceLines) {
                    $q->whereIn('servicelines.id', $userServiceLines);
                }
            )->pluck('branchname', 'id')->toArray();
        }
            $branches = array_unique($branchesServiced+$branches);
            $branches[0] = 'none';
            ksort($branches);

            return $branches;
    }
    /**
     * [delete description]
     * 
     * @param [type] $user [description]
     * 
     * @return [type]       [description]
     */
    public function delete(User $user)
    {
        // Title
        $title = 'Delete user';

        // Show the page
        return response()->view('admin/users/delete', compact('user', 'title'));
    }

    /**
     * [destroy description]
     * 
     * @param User $user [description]
     * 
     * @return [type]       [description]
     */
    public function destroy(User $user)
    {


        // Check if we are not trying to delete ourselves
        if ($user->id === auth()->user()->id) {
            // Redirect to the user management page
            return redirect()->to('admin/users')
                ->with('error', 'You cannot delete yourself');
        }
        if ($user->person->directReports()->count() >0) {
            
            $person = $user->person->load('directReports');
            return response()->view('admin.users.hasreports', compact('person'));
        }
     
        $user->person->delete();
        $user->delete();
        return redirect()->to('admin/users')
            ->with('success', 'User deleted succesfully');
    }

  
    /**
     * [import description]
     * 
     * @return [type] [description]
     */
    public function import()
    {
        $servicelines = Serviceline::whereIn('id', $this->userServiceLines)
                ->pluck('ServiceLine', 'id');
        return response()->view('admin/users/import', compact('servicelines'));
    }
    /**
     * [bulkImport description]
     * 
     * @param UserBulkImportForm $request [description]
     * 
     * @return [type]                      [description]
     */
    public function bulkImport(UserBulkImportForm $request)
    {


        $file = request()->file('upload');

        $name = time() . '-' . $file->getClientOriginalName();


        //$path = storage_path() .'/uploads/';
        $path = Config::get('app.mysql_data_loc');
        // Moves file to  mysql data folder on server
        $file->move($path, $name);
        $filename = $path . $name;


        // map the file to the fields
        $file = fopen($filename, 'r');

        $data = fgetcsv($file);
        $fields = implode(",", $data);

        $table = 'users';
        $requiredFields = ['persons'=>['firstname','lastname'],'users'=>['email','lastlogin','mgrid']];

        if ($data !== $requiredFields['users']) {
            return redirect()->back()->withErrors(['Invalid file format.  Check the fields: ']);
        }


        $temptable = $table . 'import';
        $requiredFields[$table].=",created_at,confirmed";
        $aliasfields = "p." . str_replace(",", ",p.", $requiredFields[$table]);


        $query = "DROP TABLE IF EXISTS ".$temptable;
        $error = "Can't drop table";
        $type='update';
        $result = $this->_rawQuery($query, $error, $type);


        $type='update';
        $query= "CREATE TABLE ".$temptable." AS SELECT * FROM ". $table." LIMIT 0";
        $error = "Can't create table" . $temptable;

        $result = $this->_rawQuery($query, $error, $type);

        $query = "ALTER TABLE ".$temptable." CHANGE id  id INT(10)AUTO_INCREMENT PRIMARY KEY;";
        $error = "Can't change table";
        $result = $this->_executeQuery($query);


        $this->user->_import_csv($filename, $temptable, $requiredFields[$table]);


        $this->_executeQuery("update ".$temptable." set  confirmed ='1', created_at =now()");

        $this->_executeQuery("INSERT INTO `users` (".$fields.") SELECT ".$fields." FROM `".$temptable."`");





        // Remove duplicates from import file
        $uniquefields =['email'];
        foreach ($uniquefields as $field) {
            $query ="delete from ".$temptable."
            where ". $field." in
            (SELECT ". $field." FROM (SELECT ". $field.",count(*) no_of_records
            FROM ".$temptable."  as s GROUP BY ". $field." HAVING count(*) > 1) as t)";
            $type='update';
            $error = "Can't delete the duplicates";
            $result = $this->_rawQuery($query, $error, $type);
        }

        // Add new users

        $query = "INSERT INTO `".$table."` (".$fields.")  (SELECT ". $aliasfields." FROM ".$temptable." p WHERE NOT EXISTS ( SELECT s.email FROM users s WHERE s.email = p.email))";
        $error = "I couldnt copy over to the permanent table!<br />";
        $type='insert';
        $this->_rawQuery($query, $error, $type);


        // get the user ids of the newly added users.  we should be able to use the email address
         $query = "select email from ". $temptable;
         $type = 'select';
         $error ='Couldnt get the users';
         $newUsers = $this->_rawQuery($query, $error, $type);



        $query ="DROP TABLE " .$temptable;
        $type='update';
        $error="Can't delete temporay table " . $temptable;
        $this->_rawQuery($query, $error, $type);
        // we have to assign the users to the servicelines
        // and role user
        //
        $roleid = Role::where('name', '=', 'User')->pluck('id');

        if (null!==(\Input::get('serviceline'))) {
            $servicelines = \Input::get('serviceline');

            $users = $this->user->whereIn('email', $newUsers)->get();

            foreach ($users as $user) {
                $update = User::findOrFail($user->id);
                $update->serviceline()->attach($servicelines);
                $update->roles()->attach($roleid[0]);
            }


            // here we have to sync to the user service line pivot.
        }

        return redirect()->to('/admin/users');
    }
    /**
     * [_executeQuery description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    private function _executeQuery($query)
    {

        $results = DB::statement($query);
        echo $query . ";<br />";
    }
    /**
     * [_rawQuery description]
     * @param  [type] $query [description]
     * @param  [type] $error [description]
     * @param  [type] $type  [description]
     * @return [type]        [description]
     */
    private function _rawQuery($query, $error, $type)
    {
        $result = [];
        try {
            switch ($type) {
            case 'insert':
                $result = DB::insert(DB::raw($query));
                break;
            case 'select':
                $result = DB::select(DB::raw($query));
                break;

            case 'update':
                $result = DB::select(DB::raw($query));
                break;

            default:
                $result = DB::select(DB::raw($query));
                break;
            }
            echo $query . ";<br />";
        } catch (\Exception $e) {
            echo $error . "<br />". $query;
            exit;
        }
        return $result;
    }
    /**
     * [export description]
     * 
     * @return [type] [description]
     */
    public function export()
    {
        $data = $this->user->with('person')->get();
        $export = $this->user->export($data);
        return \Response::make(rtrim($export['output'], "\n"), 200, $export['headers']);
    }

   
    /**
     * [_getManagerList description]
     * 
     * @return [type] [description]
     */
    private function _getManagerList()
    {

        $managerroles=['1','3','4','6','7','8','11','13','14'];
        
        return $this->person->select(
            \DB::raw("CONCAT(lastname ,', ',firstname) as fullname"),
            'id'
        )
            ->with('userdetails')
            ->whereHas(
                'userdetails.roles', function ($q) use ($managerroles) {
                    $q->whereIn('role_id', $managerroles);
                }
            )
            ->orderBy('fullname')

            ->pluck('fullname', 'id')
            ->toArray();
    }


   
    /**
     * [checkBranchAssignments description]
     * 
     * @return [type] [description]
     */
    public function checkBranchAssignments()
    {
        $branchpeople  = $this->person->where('lat', '!=', '')->has('branchesServiced')->with('branchesServiced')->get();
        $data = [];
        foreach ($branchpeople as $person) {
            $data[$person->id]['id']= $person->id;
            $data[$person->id]['name']= $person->postName();
            $data[$person->id]['address']= $person->address;

            foreach ($person->branchesServiced as $branch) {
                $distance = $this->person->distanceBetween($person->lat, $person->lng, $branch->lat, $branch->lng);
                if ($distance >100) {
                    $data[$person->id]['branches'][$branch->id]['id']= $branch->id;
                    $data[$person->id]['branches'][$branch->id]['branchname']= $branch->branchname;
                    $data[$person->id]['branches'][$branch->id]['distance']= $distance;
                    $data[$person->id]['branches'][$branch->id]['address'] = $branch->fullAddress();
                }
            }
        }
    
        return response()->view('admin.branches.checkbranches', compact('data'));
    }
    /**
     * Return all deleted users
     * 
     * @return View [description]
     */
    public function deleted()
    {
        $users = $this->user->onlyTrashed()->with('deletedperson', 'roles')->get();

        return response()->view('admin.users.deleted', compact('users'));
    }
    /**
     * Restre soft deleted person
     * 
     * @param Int $id id of deleted user
     * 
     * @return [type]           [description]
     */
    public function restore(Int $id)
    {
        $user = $this->user->onlyTrashed()->with('deletedperson')->findOrFail($id);
        $user->restore();
        $user->deletedperson->restore();
        return redirect()->route('deleted.users')->withMessage($user->deletedperson->fullName() . ' has been restored');
    }

    /**
     * [permdeleted description]
     * 
     * @param Int     $id      [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function permdeleted(Int $id, Request $request)
    {
       
        $user = $this->user->onlyTrashed()->with('deletedperson')->findOrFail($id);
        $user->deletedperson->forceDelete();
        $user->forceDelete();

        return redirect()->route('deleted.users')->withWarning($user->deletedperson->fullName() . ' has been permanently deleted');
    }
}
