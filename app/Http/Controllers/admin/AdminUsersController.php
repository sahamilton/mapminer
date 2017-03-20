<?php
namespace App\Http\Controllers\Admin;
use App\User;
use App\Role;
use App\Person;
use App\Permission;
use App\Http\Requests\UserFormRequest;
use App\Branch;
use App\Track;
use App\Serviceline;
use App\SearchFilter;
use App\Http\Controllers\BaseController;

class AdminUsersController extends BaseController {


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
     * Person Model
     * @var Person
     */
    protected $person;


    /**
     * Permission Model
     * @var Permission
     */
    protected $permission;

    /**
     * Servicelines array
     * @var userServiceLines
     */
    public $userServiceLines;

    public $branch;
    public $serviceline;

    /**
     * Inject the models.
     * @param User $user
     * @param Role $role
     * @param Permission $permission
     * @param Person $person
     * @param Track $track
     * 
     */
    public function __construct(User $user, Role $role, Person $person, Permission $permission, Branch $branch, Track $track,Serviceline $serviceline)
    {
        
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
        $this->person = $person;
        $this->track = $track;
        $this->branch = $branch;
        $this->serviceline = $serviceline;
                
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id=NULL)
    {
       $this->userServiceLines = $this->branch->getUserServiceLines();

        $title = 'People / User Management';

        if(! $id)
        // Grab all the users
	       {
	       		$users = $this->user
	       	       ->with('roles','usage','person','serviceline')
	       	       ->whereHas('serviceline', function($q)  {
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
	       	       ->get();
	       	       $serviceline = 'All';
	       	}else{
				
                $servicelines = $this->serviceline->find($id);
				
				$users = $this->user
	       	       ->with('roles','usage','person')
	       	       ->whereHas('serviceline', function($q) use ($servicelines) {
					    $q->whereIn('serviceline_id',[$servicelines->id]);

					})
	       	       ->get();
              
	       	       $serviceline = $servicelines->ServiceLine;
                   $title = $serviceline ." users";
	       	}
		
	       	
        // Show the page
        return response()->view('admin/users/index', compact('users', 'title','serviceline'));
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
        $selectedRoles = \Input::old('roles', array());

        // Selected permissions
        $selectedPermissions = \Input::old('permissions', array());

		// Title
		$title = 'Create New User';

		// Mode
		$mode = 'create';

		// Service lines
		$servicelines = $this->person->getUserServiceLines();
		
		$branches = $this->getUsersBranches($this->user);
		$verticals = SearchFilter::where('searchcolumn','=','vertical')
		->where('type','!=','group')			
		->pluck('filter','id');
		
		$managerlist = $this->getManagerList();
		// Show the page
		return response()->view('admin/users/create', compact('roles', 'permissions', 'verticals','selectedRoles', 'selectedPermissions', 'title', 'mode','managerlist','servicelines','branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(UserFormRequest $request)
    {
        
        $user = $this->user->create($request->all());
	        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->password = \Hash::make(\Input::get('password'));
        if ($request->has('confirm')) {
            $user->confirmed = $request->get('confirm');
        }

        

        if ( $user->save() ) {
            
			$person = new Person;
			$person = $this->updateAssociatedPerson($person,$request->all());        
			$user->person()->save($person);
            $track=Track::create(['user_id'=>$user->id]);		
            $user->saveRoles($request->get( 'roles' ));
            $user->serviceline()->attach($request->get('serviceline'));
	        $person->rebuild();
            return redirect()->to('admin/users/')
                ->with('success', 'User created succesfully');

        } else {
           
            return redirect()->to('admin/users/create')
                ->withInput($request->except('password'))
                ->with( 'error', 'Unable to create user' );
        }
    }

   
    /**
     * Display the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function show($id)
    {
        $user = $this->user->findOrFail($id);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function edit($userid)
    {
        $servicelines = $this->person->getUserServiceLines();
        $user = $this->user
          ->with('serviceline','person','person.branchesServiced','roles')
          ->find($userid->id);

	    if ( $user )
        {
            $roles = $this->role->all();
            $permissions = $this->permission->all();

            // Title
        	$title = 'Update user';
        	// mode
        	$mode = 'edit';
			$managerlist = $this->getManagerList();
			
			$branchesServiced = $user->person->branchesServiced->pluck('id');
			
			// Ether get close branches 
			
			$branches = $this->getUsersBranches($user);
		
			$verticals = SearchFilter::where('searchcolumn','=','vertical')
			->where('type','!=','group')			
			->pluck('filter','id');
        
             $verticals = ['0' => 'none'] + $verticals->toArray();
        	return response()->view('admin.users.edit', compact('user', 'roles', 'permissions', 'verticals','title', 'mode','managerlist','servicelines','branches','branchesServiced'));
        }
        else
        {
            return redirect()->to(route('users.index'))->with('error', 'User does not exist');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function update(UserFormRequest $request,$user)
    {
        $user = $this->user->with('person')->find($user->id);
        $oldUser = clone($user);
      
		if($user->update($request->all())){
            $person = $this->updateAssociatedPerson($user,$request->all());
            $person = $this->associateBranchesWithPerson($person,$request->all());
      
           if($request->has('serviceline')){

                $user->serviceline()->sync($request->get('serviceline'));
        	}

        	

            return redirect()->to(route('users.index'))->with('success', 'User updated succesfully');
        }else{
            
            return redirect()->to('admin/users/' . $user->id . '/edit')
                ->with('error', 'Unable to update user');
        }

    }
    private function associateBranchesWithPerson($person, $data)
    {
         if(isset($data['branchstring']) or isset($data['branches'])){
            
            if(isset($data['branchstring'])){
              $branches = $this->branch->getBranchIdFromBranchNumber($data['branchstring']);
            }else{
                $branches = $data['branches'];
            }
            
            if(count($branches)>0)
            {
                $syncData=array();
            }else{
                foreach ($branches as $branch){
                    $syncData[$branch]=['role_id'=>5];
                }
            }
            $person->branchesServiced()->sync($syncData);
        }

        $person->rebuild();
        return $person;
    }

    private function updateAssociatedPerson($person,$data){

        $person->update($data);
        $person->reports_to  = $data['manager'];
        
        if(isset($data['vertical'])){
            $person->industryfocus()->sync($data['vertical']);
        }
        if(isset($data['branches'])){
            $person->branchesServiced()->sync($data['branches']);
        }
        if(isset($data['address'])){

            $person->address = $data['address'];
            $latLng = $this->getLatLng($person->address);
           
           
            $person->lat = $latLng[0]['latitude'];
            $person->lng = $latLng[0]['longitude'];
            if(isset($data['city'])){
                $person->city = $data['city'];
            }else{
                 $person->city = $latLng[0]['locality'];
            }
            
           if(isset($data['state'])){
                $user->person->state =$data['state'];
            }else{
                 $user->person->state = $latLng[0]['adminLevels'][1]['code'];
            }
       }else{
            $person->lat = null;
            $person->lng = null;
            $person->city = null;
            $person->state = null;
        }
        $person->save();
        return $person;
    }

    private function getUsersBranches($user){
			if(isset($user->person->lat) && $user->person->lat !=0){

				$userServiceLines= $user->serviceline->pluck('id')->toArray();
             
				$nearbyBranches = $this->branch->findNearbyBranches($user->person->lat,$user->person->lng,100,100,$userServiceLines);
               
				$branches[0] = 'none';
				foreach($nearbyBranches as $nearbyBranch){

					$branches[$nearbyBranch->branchid ]= $nearbyBranch->branchname;
				}
			// or all branches	
			}else{
				$branches = Branch::select(\DB::raw("CONCAT_WS(' / ',branchname,branchnumber) AS name"),'id')->pluck('name','id');
			}
            
			return $branches;
		}	
    /**
     * Remove user page.
     *
     * @param $user
     * @return Response
     */
    public function delete($user)
    {
        // Title
        $title = 'Delete user';

        // Show the page
        return response()->view('admin/users/delete', compact('user', 'title'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param $user
     * @return Response
     */
    public function destroy($user)
    {
        
		//$user = $this->user->find($id);
        //dd($user);
		// Check if we are not trying to delete ourselves
        if ($user->id === \Auth::user()->id)
        {
            // Redirect to the user management page
            return redirect()->to('admin/users')
            ->with('error', 'You cannot delete yourself');
        }
  
        $user->delete();
		return redirect()->to('admin/users')
		->with('success', 'User deleted succesfully');
       
    }
	
	
	public function import()
	{
		$servicelines = Serviceline::whereIn('id',$this->userServiceLines)
				->pluck('ServiceLine','id');
		return response()->view('admin/users/import',compact('servicelines'));
		
	}

   public function bulkImport()
	{
		$rules= ['upload' => 'required'];
   		
		// Make sure we have a file
		$validator = Validator::make(\Input::all(), $rules);

    	if ($validator->fails())
		{
			
			return Redirect::back()
			->withErrors($validator);
		}

		// Make sure its a CSV file - test #1
		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
		if(!in_array($_FILES['upload']['type'],$mimes)){
			
		 	return Redirect::back()->withErrors(['Only CSV files are allowed']);
		}

		$file = \Input::file('upload');
		$name = time() . '-' . $file->getClientOriginalName();


		//$path = storage_path() .'/uploads/';
		$path = Config::get('app.mysql_data_loc');
		// Moves file to  mysql data folder on server
		$file->move($path, $name);
		$filename = $path . $name;	
		
		
		// map the file to the fields
		$file = fopen($filename, 'r');

		$data = fgetcsv($file);
		$fields = implode(",",$data);

		$table = 'users';
		$requiredFields = ['persons'=>['firstname','lastname'],'users'=>['username','email','lastlogin','mgrid']];

		if($data !== $requiredFields['users']){
			
			return Redirect::back()->withErrors(['Invalid file format.  Check the fields: ']);
		}

		
		$temptable = $table . 'import';		
		$requiredFields[$table].=",created_at,confirmed";
		$aliasfields = "p." . str_replace(",",",p.",$requiredFields[$table]);
		
		
		$query = "DROP TABLE IF EXISTS ".$temptable;
		$error = "Can't drop table";
		$type='update';
		$result = $this->rawQuery($query,$error,$type);
		
		
		$type='update';
		$query= "CREATE TABLE ".$temptable." AS SELECT * FROM ". $table." LIMIT 0";
		$error = "Can't create table" . $temptable;
		
		$result = $this->rawQuery($query,$error,$type);
		
		$query = "ALTER TABLE ".$temptable." CHANGE id  id INT(10)AUTO_INCREMENT PRIMARY KEY;";
		$error = "Can't change table";
		$result = $this->executeQuery($query);
		
	
		

	// Load the data file
	
		$this->user->_import_csv($filename, $temptable,$requiredFields[$table]);
	
		
		$this->executeQuery("update ".$temptable." set  confirmed ='1', created_at =now()");
	
		$this->executeQuery("INSERT INTO `users` (".$fields.") SELECT ".$fields." FROM `".$temptable."`");
		
		
		
		
		
		// Remove duplicates from import file
		$uniquefields =['email','username'];
		foreach($uniquefields as $field) {
			$query ="delete from ".$temptable." 
			where ". $field." in 
			(SELECT ". $field." FROM (SELECT ". $field.",count(*) no_of_records 
			FROM ".$temptable."  as s GROUP BY ". $field." HAVING count(*) > 1) as t)";
			$type='update';
			$error = "Can't delete the duplicates";
			$result = $this->rawQuery($query,$error,$type);
		}
		
		// Add new users
		
		$query = "INSERT INTO `".$table."` (".$fields.")  (SELECT ". $aliasfields." FROM ".$temptable." p WHERE NOT EXISTS ( SELECT s.username FROM users s WHERE s.username = p.username or s.email = p.email))";
		$error = "I couldnt copy over to the permanent table!<br />";
		$type='insert';
		$this->rawQuery($query,$error,$type);
		

		// get the user ids of the newly added users.  we should be able to use the username
		 $query = "select username from ". $temptable;
		 $type = 'select';
		 $error ='Couldnt get the users';
		 $newUsers = $this->rawQuery($query,$error,$type);
		 
		
			
		$query ="DROP TABLE " .$temptable;
		$type='update';
		$error="Can't delete temporay table " . $temptable;
		$this->rawQuery($query,$error,$type);
		// we have to assign the users to the servicelines
		// and role user
		// 
		$roleid = Role::where('name','=','User')->pluck('id');
	
		if (null!==(\Input::get('serviceline'))){
			$servicelines = \Input::get('serviceline');
			
			$users = $this->user->whereIn('username',$newUsers)->get();

			foreach ($users as $user){

				$update = User::findOrFail($user->id);
				$update->serviceline()->attach($servicelines);
				$update->roles()->attach($roleid[0]);
			}

			
			// here we have to sync to the user service line pivot.
		}

		return redirect()->to('/admin/users');
		
	}
	
	private function executeQuery($query)
	{
		
		$results = DB::statement($query);
		echo $query . ";<br />";
	}
	private function rawQuery($query,$error,$type){
		$result = array();
		try{
			switch ($type) {
				case 'insert':
					$result = DB::insert( DB::raw($query ) );
					break;
				case 'select':
					$result = DB::select( DB::raw($query ) );
				break;
				
				case 'update':
					$result = DB::select( DB::raw($query ) );
				break;
			
				default:
					$result = DB::select( DB::raw($query ) );
				break;
			}
			echo $query . ";<br />";		
		}
		catch (\Exception $e){
			echo $error . "<br />". $query;
			exit;
		}
		return $result;
	}
	
	public function export(){
		$data = $this->user->with('person')->get();
		$export = $this->user->export($data);	
		return \Response::make(rtrim($export['output'], "\n"), 200, $export['headers']);
	}
	
	
	
	private function getManagerList()
	{
		$managerroles=['2','3','4','5','6','7','8'];
		$managers = $this->user->whereHas('roles', 
			function($q) use($managerroles){
			$q->whereIn('role_id',$managerroles);
			})->with('person')->get();
		$managerlist= array();
		foreach($managers as $manager){
			$managerlist[$manager->person->id] = $manager->person->lastname . ",". $manager->person->firstname;
		}
		asort($managerlist);
		return $managerlist;	
	}


	
	private function getLatLng($address)
	{
		try {
			
		$geocode = \Geocoder::geocode($address)->get();
		// The GoogleMapsProvider will return a result
		return $geocode;
		
		} catch (\Exception $e) {
			// No exception will be thrown here
			//echo $e->getMessage();
		}
		
	}
	
}
