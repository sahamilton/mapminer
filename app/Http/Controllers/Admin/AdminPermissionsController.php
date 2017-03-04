<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use App\User;
use App\Role;
use App\Permission;
class AdminPermissionsController extends BaseController {

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
     * 
     * 
     */
    public function __construct(User $user, Role $role,  Permission $permission)
    {
        parent::__construct();
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
        
        
    }


	
	/**
	 * Display a listing of the resource.
	 * GET /adminpermissions--resource
	 *
	 * @return Response
	 */
	public function index()
	{
		$permissions = $this->permission->with('roles')->get();
		return View::make('admin.permissions.index', compact('permissions'));

	}

	/**
	 * Show the form for creating a new resource.
	 * GET /adminpermissions--resource/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$roles = $this->role->all();

        // Selected permissions
        $selectedRoles = Input::old('roles', array());

        // Title
        $title = 'Create New Permission';

        // Show the page
        return View::make('admin/permissions/create', compact('roles', 'selectedRoles', 'title'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /adminpermissions--resource
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), ['name' => 'required']);
        // Check if the form validates with success
        if ($validator->passes())
        {
  	    // Get the inputs, with some exceptions
            $inputs = Input::except('_token');
			$inputs['display_name'] = ucwords($inputs['name']);
            $inputs['name'] = strtolower(str_replace(' ','_',$inputs['name']));
			
            $permission = Permission::create($inputs);

            if ($permission->id)
            {
                
                $permission->roles()->sync($inputs['roles']);
                // Redirect to the new role page
                return Redirect::to(route('admin.permissions.index'))->with('success', 'Permission created succesfully');
            }

            // Redirect to the new role page
            return Redirect::to(route('admin.permissions.index'))->with('error', 'Unable to create permission');
		}
            // Redirect to the role create page
            return Redirect::to(route('admin.permissions.create'))->withInput()->with('error', 'Unable to create permission');
        
	}

	/**
	 * Display the specified resource.
	 * GET /adminpermissions--resource/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		dd($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /adminpermissions--resource/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$roles = $this->role->all();
		$permission = $this->permission->with('roles')->find($id);
		if($permission){

	        // Selected permissions
	       
	        $selectedRoles = $permission->roles->lists('id');
	        
	        // Title
	        $title = 'Edit Permission';

	        // Show the page
        	return View::make('admin/permissions/edit', compact('roles', 'selectedRoles', 'permission','title'));
        }
        return Redirect::to(route('admin.permissions.index'))->with('error', 'Unable to locate that permission');
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /adminpermissions--resource/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{

		$validator = Validator::make(Input::all(), ['name' => 'required']);
        // Check if the form validates with success
        if ($validator->passes())
        {
  	    // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');
            $permission = $this->permission->find(Input::get('permission_id'));
            

            if ($permission->update($inputs))
            {
                
                $permission->roles()->sync($inputs['roles']);
                // Redirect to the new role page
                return Redirect::to(route('admin.permissions.index'))->with('success', 'Permission updated succesfully');
            }

            // Redirect to the new role page
            return Redirect::to(route('admin.permissions.index'))->withInput()->with('error', 'Unable to update permission');
		}
            // Redirect to the role create page
        return Redirect::to(route('admin.permissions.edit'))->withInput()->with('error', 'Unable to update permission');
        
	}
		

	/**
	 * Remove the specified resource from storage.
	 * DELETE /adminpermissions--resource/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		 $permission = $this->permission->find($id);
		 if($permission && $permission->delete()) {
                // Redirect to the role management page
                return Redirect::to(route('admin.permissions.index') )->with('success', 'Permission succesfully deleted');
            }

        // There was a problem deleting the permission
           

		return Redirect::to(route('admin.permissions.index'))->with('error', 'Unable to delete that permission');
	}

}