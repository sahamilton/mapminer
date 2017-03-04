<?php
namespace App\Http\Controllers\admin;

use App\User;
use App\Role;
use App\Permission;
use App\Http\Controllers\BaseController;

class AdminRolesController extends BaseController {


    /**
     * User Model
     * @var User
     */
    protected $user;

    /**
     * Role Model
     * @var Role
     */
    protected $role;

    /**
     * Permission Model
     * @var Permission
     */
    protected $permission;

    /**
     * Inject the models.
     * @param User $user
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(User $user, Role $role, Permission $permission)
    {
        parent::__construct();
        $this->user = $user;
		$this->role = $role;
		$this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

		// Title
        $title = "Role Management";

		$roles = $this->role->with('assignedRoles')->get();
        // Show the page
        return \View::make('admin/roles/index', compact('roles', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get all the available permissions
        $permissions = $this->permission->all();

        // Selected permissions
        $selectedPermissions = \Input::old('permissions', array());

        // Title
        $title = 'Create New Role';

        // Show the page
        return \View::make('admin/roles/create', compact('permissions', 'selectedPermissions', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

        // Declare the rules for the form validation
        $rules = array(
            'name' => 'required'
        );

        // Validate the inputs
        $validator = Validator::make(\Input::all(), $rules);
        // Check if the form validates with success
        if ($validator->passes())
        {
  	    // Get the inputs, with some exceptions
            $inputs = \Input::except('csrf_token');

            $role = Role::create($inputs);
            $role= new Role;
            $role->name = $inputs['name'];
            $role->save();

            //dd($this->role->fillable);
            
            dd($role);
            

            // Was the role created?
            if ($this->role->id)
            {
                // Save permissions
                $this->role->perms()->sync($this->permission->preparePermissionsForSave($inputs['permissions']));
                // Redirect to the new role page
                return Redirect::to('admin/roles/' . $role->id . '/edit')->with('success', 'Role created succesfully');
            }

            // Redirect to the new role page
            return Redirect::to('admin/roles/create')->with('error', 'Unable to create role');

            // Redirect to the role create page
            return Redirect::to(route('admin/roles/create'))->withInput()->with('error', 'Unable to create role - ' . $error);
        }

        // Form validation failed
        return Redirect::to('admin/roles/create')->withInput()->withErrors($validator);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $role= $this->role->find($id);
	$users = $this->user->whereHas('roles', 
			function($q) use($role){
			$q->where('role_id', $role->id);
			})->with('roles','usage','person','serviceline')->get();
			$title =$role->name .' users';
		//$users = $this->role->findOrFail($role->id)->with('assignedRoles')->get();
		return \View::make('admin/roles/show', compact('users','role','title'));
		// redirect to the frontend
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $role
     * @return Response
     */
    public function edit($role)
    {
        if(! empty($role))
        {
            $permissions = $this->permission->preparePermissionsForDisplay($role->perms()->get());
        }
        else
        {
            // Redirect to the roles management page
            return Redirect::to('admin/roles')->with('error', 'Role does not exist');
        }

        // Title
        $title = 'Update Role';

        // Show the page
        return \View::make('admin/roles/edit', compact('role', 'permissions', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $role
     * @return Response
     */
    public function update($role)
    {
        // Declare the rules for the form validation
        $rules = array(
            'name' => 'required'
        );

        // Validate the inputs
        $validator = Validator::make(\Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            // Update the role data
            $role->name = \Input::get('name');
            $role->perms()->sync($this->permission->preparePermissionsForSave(\Input::get('permissions')));

            // Was the role updated?
            if ($role->save())
            {
                // Redirect to the role page
                return Redirect::to('admin/roles/' . $role->id . '/edit')->with('success', 'Role updated succesfully');
            }
            else
            {
                // Redirect to the role page
                return Redirect::to('admin/roles/' . $role->id . '/edit')->with('error', 'Unable to update role');
            }
        }

        // Form validation failed
        return Redirect::to('admin/roles/' . $role->id . '/edit')->withInput()->withErrors($validator);
    }


    /**
     * Remove user page.
     *
     * @param $role
     * @return Response
     */
    public function delete($role)
    {
        // Title
        $title = 'Delete role';

        // Show the page
        return \View::make('admin/roles/delete', compact('role', 'title'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param $role
     * @internal param $id
     * @return Response
     */
    public function destroy($role)
    {
            // Was the role deleted?
            if($role->delete()) {
                // Redirect to the role management page
                return Redirect::to('admin/roles')->with('success', 'Role deleted succesfully');
            }

            // There was a problem deleting the role
            return Redirect::to('admin/roles')->with('error', 'Unable to delete role');
    }

  

}
