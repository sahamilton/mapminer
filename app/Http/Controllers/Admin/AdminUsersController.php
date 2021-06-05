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

        
        return response()->view('admin.users.index', compact('serviceline'));
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
        $selectedRoles = [];

        // Selected permissions
        $selectedPermissions = [];

        // Title
        $title = 'Create New User';

        // Mode
        $mode = 'create';

        // Service lines

        $servicelines = auth()->user()->currentServiceLineIds();
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
        
        if (request()->filled('confirm')) {
            $user->confirmed = request('confirm');
        }

        if ($user->save()) {
            $user->saveRoles(request('roles'));
            $this->_updatePassword($request, $user);
            $this->_updateServicelines($request, $user);
            $person = $this->person->create($this->_createPersonData($request, $user));
            $this->_updateIndustryVertical($request, $person);
            $this->_associateBranchesWithPerson($request, $person);
            $track=Track::create(['user_id'=>$user->id]);
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
        // note no update of address / geocoding!
        //$user->load('person');
        //$oldUser = clone($user);

        if ($user->update(request()->except('password'))) {
            $user->load('person');
            $user->saveRoles(request('roles'));
            $this->_updatePassword($request, $user);
            $this->_updateServicelines($request, $user);
            $user->person->update($this->_createPersonData($request, $user));
            $this->_updateIndustryVertical($request, $user->person);
            $this->_associateBranchesWithPerson($request, $user->person);
            return redirect()->route('person.details', $user->person->id)
                ->with('success', 'User updated succesfully');
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

    private function _createPersonData(Request $request, User $user)
    {
        $personName = request(['firstname','lastname','phone','business_title', 'reports_to']);
        $personGeo = $this->person->updatePersonsAddress($request);
        return array_merge($personGeo, $personName, ['user_id'=>$user->id]);
        
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

        } else {
            $user->serviceline()->sync([]);
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

            if ($verticals[0] == 0) {
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
     * @param Request $request [description]
     * @param Person  $person  [description]
     * 
     * @return Person          [description]
     */
    private function _associateBranchesWithPerson(Request $request, Person $person)
    {
        $data = request()->all();

        $syncData=[];

        if (request()->filled('branchstring')) {
            $data['branches'] = $this->branch->getBranchIdFromid(request('branchstring'));
        }
        
        if (isset($data['branches']) && count($data['branches']) > 0 && $data['branches'][0] != 0) {

            $data['roles'] = $person->userdetails->roles->pluck('id')->toArray();
            foreach ($data['branches'] as $branch) {
                if ($data['roles']) {
                    foreach ($data['roles'] as $role) {
                        $syncData[$branch]=['role_id'=>$role];
                    }
                }
            }
        }
        
        $person->branchesServiced()->sync($syncData);

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

        if (isset($data['vertical']) && $data['vertical'][0]!=0) {
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
        $branches = array_unique($branchesServiced + $branches);
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
            
            $person = $user->person->load('directReports', 'reportsTo');
            
            return response()->view('admin.users.hasreports', compact('person'));
        }
     
        $user->person->delete();
        $user->delete();
        return redirect()->to('admin/users')
            ->with('success', 'User deleted succesfully');
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
     * Restore soft deleted person
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
        return redirect()->route('users.index')->withMessage($user->deletedperson->fullName() . ' has been restored');
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

        return redirect()->route('users.index')
            ->withWarning($user->deletedperson->fullName() . ' has been permanently deleted');
    }

    public function bulkdelete()
    {
        return response()->view('admin.users.import.deleteusers');
    }

    public function confirmDelete(Request $request)
    {
        $users = $this->user
            ->with('person', 'person.reportsTo', 'person.directReports')
            ->whereIn('employee_id', explode("\r\n", request('user_ids')))
            ->get();
        return response()->view('admin.users.import.deleteconfirm', compact('users'));
    }

    public function massDelete(Request $request)
    {
        $this->user->whereIn('id', request('user_id'))->delete();
        return redirect()->route('users.index')->withMessage('Users deleted');
    }
}
