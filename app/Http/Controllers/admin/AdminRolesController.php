<?php
namespace App\Http\Controllers\Admin;

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
        return response()->view('admin/roles/index', compact('roles', 'title'));
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
        $currentPermissions  = \Input::old('permissions', array());

        // Title
        $title = 'Create New Role';

        $role= new Role;

        // Show the page
        return response()->view('admin.roles.create', compact('permissions','role', 'currentPermissions', 'title'));
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
        $validator = \Validator::make(\Input::all(), $rules);
        // Check if the form validates with success
        if ($validator->passes())
        {
  	    // Get the inputs, with some exceptions
            $inputs = \Input::except('csrf_token');

            $role = Role::create($inputs);
            $role= new Role;
            $role->name = $inputs['name'];
            $role->save();

          
           
            // Was the role created?
            if ($role->id)
            {
                // Save permissions
                $role->permissions()->sync($inputs['permissions']);
                // Redirect to the new role page
                return redirect()->to(route('roles.index'))->with('success', 'Role created succesfully');
            }

            // Redirect to the new role page
            return redirect()->to('admin/roles/create')->with('error', 'Unable to create role');

            // Redirect to the role create page
            return redirect()->to(route('admin/roles/create'))->withInput()->with('error', 'Unable to create role - ' . $error);
        }

        // Form validation failed
        return redirect()->to('admin/roles/create')->withInput()->withErrors($validator);
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
		return response()->view('admin/roles/show', compact('users','role','title'));
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
            $permissions = $this->permission->all();
        }
        else
        {
            // Redirect to the roles management page
            return redirect()->to('admin/roles')->with('error', 'Role does not exist');
        }

        // Title
        $title = 'Update Role';
        $currentPermissions = $this->role->getCurrentPermissions($role);

        // Show the page
        return response()->view('admin/roles/edit', compact('role', 'permissions', 'title','currentPermissions'));
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
        $permissions = array();
        if(\Input::has('permissions')){
           $permissions =\Input::get('permissions');
        }


        // Validate the inputs
        $validator = \Validator::make(\Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            // Update the role data
            $role->name = \Input::get('name');
            $role->permissions()->sync($permissions);

            // Was the role updated?
            if ($role->save())
            {
                // Redirect to the role page
                return redirect()->to(route('roles.index'))->with('success', 'Role updated succesfully');
            }
            else
            {
                // Redirect to the role page
                return redirect()->to(route('roles.edit',$role->id))->with('error', 'Unable to update role');
            }
        }

        // Form validation failed
        return redirect()->to(route('roles.edit',$role->id))->withInput()->withErrors($validator);
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
        return response()->view('admin/roles/delete', compact('role', 'title'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param $role
     * @internal param $id
     * @return Response
     */
    public function purge($role)
    {
           
            // Was the role deleted?
            if($role->delete()) {
                // Redirect to the role management page
                return redirect()->to(route('roles.index'))->with('success', 'Role deleted succesfully');
            }

            // There was a problem deleting the role
            return redirect()->to('admin/roles')->with('error', 'Unable to delete role');
    }

  

}
