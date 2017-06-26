<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use App\User;
use App\Role;
use App\Http\Requests\PermissionFormRequest;
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
		return response()->view('admin.permissions.index', compact('permissions'));

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
        $currentRoles = \Input::old('roles', array());

        $permission = new Permission;
        $title = 'Create New Permission';

        // Show the page
        return response()->view('admin/permissions/create', compact('roles', 'permission','currentRoles', 'title'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /adminpermissions--resource
	 *
	 * @return Response
	 */
	public function store(PermissionFormRequest $request)
	{
		
  	    // Get the inputs, with some exceptions
            $inputs = $request->except('_token');
			$inputs['display_name'] = ucwords($inputs['name']);
            $inputs['name'] = strtolower(str_replace(' ','_',$inputs['name']));
			
            $permission = $this->permission->create($inputs);

            if ($permission->id)
            {
                
                $permission->roles()->sync($inputs['roles']);
                // Redirect to the new role page
                return redirect()->to(route('permissions.index'))->with('success', 'Permission created succesfully');
            }

            // Redirect to the new role page
            return redirect()->to(route('permissions.index'))->with('error', 'Unable to create permission');
		
            // Redirect to the role create page
            return redirect()->to(route('permissions.create'))->withInput()->with('error', 'Unable to create permission');
        
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
	public function edit(Permission $permission)
	{
		
		$roles = $this->role->all();
		$permission = $this->permission->with('roles')->find($permission->id);
		if($permission){

	        // Selected permissions

	        $currentRoles = $permission->roles()->pluck('roles.id')->toArray();
	        
	        // Title
	        $title = 'Edit Permission';

	        // Show the page
        	return response()->view('admin.permissions.edit', compact('roles', 'currentRoles', 'permission','title'));
        }
        return redirect()->to(route('permissions.index'))->with('error', 'Unable to locate that permission');
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /adminpermissions--resource/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Permission $permission, PermissionFormRequest $request)
	{
		
		
  	    // Get the inputs, with some exceptions
            $inputs = $request->except(['csrf_token']);
            $inputs['display_name'] = ucwords($inputs['name']);
            $inputs['name'] = strtolower(str_replace(' ','_',$inputs['name']));
            if ($permission->update($inputs))
            {
                if($request->has('roles')){
                	$permission->roles()->sync($inputs['roles']);
                }else{
                	$permission->roles()->detach();
                }
                // Redirect to the new role page
                return redirect()->to(route('permissions.index'))->with('success', 'Permission updated succesfully');
            }

            // Redirect to the new role page
            return redirect()->to(route('permissions.index'))->withInput()->with('error', 'Unable to update permission');
      
        
	}
		

	/**
	 * Remove the specified resource from storage.
	 * DELETE /adminpermissions--resource/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function purge($permission)
	{

		 if($permission && $permission->delete()) {
                // Redirect to the role management page
                return redirect()->to(route('permissions.index') )->with('success', 'Permission succesfully deleted');
            }

        // There was a problem deleting the permission
           

		return redirect()->to(route('permissions.index'))->with('error', 'Unable to delete that permission');
	}

}