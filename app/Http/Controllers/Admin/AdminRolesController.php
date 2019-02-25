<?php
namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;
use App\Permission;
use App\Http\Controllers\BaseController;
use App\Http\Requests\RoleFormRequest;

class AdminRolesController extends BaseController
{


    /**
     * User Model
     * @var User
     */
    public $user;

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

        $currentPermissions = null;
        // Title
        $title = 'Create New Role';

        $role= new Role;

        // Show the page
        return response()->view('admin.roles.create', compact('permissions', 'role', 'currentPermissions', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(RoleFormRequest $request)
    {
       
            

        if (! $role = Role::create(request()->all())) {
            return redirect()->to('admin/roles/create')->with('error', 'Unable to create role');
        }
         
        if (request()->filled('permissions')) {
            // Save permissions
            $role->permissions()->sync($request->permissions);
            // Redirect to the new role page
        }

            return redirect()->to(route('roles.index'))->with('success', 'Role created succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($role)
    {


        $users = $this->user->whereHas(
            'roles',
            function ($q) use ($role) {
                    $q->where('role_id', $role->id);
            }
        )
        ->with('roles', 'usage', 'person', 'serviceline')
        ->get();
        $title =$role->name .' users';
        //$users = $this->role->findOrFail($role->id)->with('assignedRoles')->get();
        return response()->view('admin/roles/show', compact('users', 'role', 'title'));
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
        
        if (! empty($role)) {
            $permissions = $this->permission->all();
        } else {
            // Redirect to the roles management page
            return redirect()->to('admin/roles')->with('error', 'Role does not exist');
        }

        // Title
        $title = 'Update Role';
        $currentPermissions = $this->role->getCurrentPermissions($role);

        // Show the page
        return response()->view('admin/roles/edit', compact('role', 'permissions', 'title', 'currentPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $role
     * @return Response
     */
    public function update(RoleFormRequest $request, $role)
    {
        // Declare the rules for the form validation
        
        $permissions = array();

        if (request()->filled('permissions')) {
            $permissions =request('permissions');
        }


        $role->name = request('name');

        $role->permissions()->sync($permissions);

        // Was the role updated?
        if ($role->save()) {
            // Redirect to the role page
            return redirect()->to(route('roles.index'))->with('success', 'Role updated succesfully');
        } else {
            // Redirect to the role page
            return redirect()->to(route('roles.edit', $role->id))->with('error', 'Unable to update role');
        }
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
    public function destroy($role)
    {
           
            // Was the role deleted?
        if ($role->delete()) {
            // Redirect to the role management page
            return redirect()->to(route('roles.index'))->with('success', 'Role deleted succesfully');
        }

            // There was a problem deleting the role
            return redirect()->to('admin/roles')->with('error', 'Unable to delete role');
    }
}
