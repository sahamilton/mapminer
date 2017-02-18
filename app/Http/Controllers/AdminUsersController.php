<?php
namespace App\Http\Controllers;
use App\Person;
use App\Permission;
use App\Role;

class AdminUsersController extends AdminController {


    /**
     * User Model
     * @var User
     */
    protected $user;

      /**
     * Person Model
     * @var Person
     */
    protected $person;

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
     * Servicelines array
     * @var userServiceLines
     */
    public $userServiceLines;

    /**
     * Inject the models.
     * @param User $user
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(User $user, Role $role, Person $person, Permission $permission)
    {
        parent::__construct();
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
        $this->person = $person;
        parent::__construct();

        
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex($serviceline=NULL)
    {
        // Title
       
        $title = Lang::get('admin/users/title.user_management');

        if(! $serviceline)
        // Grab all the users
	       {
	       		$users = $this->user
	       	       ->with('roles','usage','person')
	       	       ->whereHas('serviceline', function($q)  {
					    $q->whereIn('serviceline_id',$this->userServiceLines);

					})
	       	       ->get();
	       	       $serviceline = 'All';
	       	}else{
				
				$currentServiceline = explode(',',$serviceline->id);
				$users = $this->user
	       	       ->with('roles','usage','person')
	       	       ->whereHas('serviceline', function($q) use ($currentServiceline) {
					    $q->whereIn('serviceline_id',$currentServiceline)
					    ->whereIn('serviceline_id',$this->userServiceLines);

					})
	       	       ->get();
	       	       $serviceline = $serviceline->ServiceLine;
	       	}
		
	       	
        // Show the page
        return view()->make('admin/users/index', compact('users', 'title','serviceline'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
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
		$title = Lang::get('admin/users/title.create_a_new_user');

		// Mode
		$mode = 'create';

		// Service lines
		
		$servicelines = Serviceline::whereIn('id',$this->userServiceLines)
						->pluck('ServiceLine','id');

		$verticals = SearchFilter::where('searchcolumn','=','vertical')
		->where('type','!=','group')			
		->pluck('filter','id');
		
		$managerlist = $this->getManagerList();
		// Show the page
		return view()->make('admin/users/create_edit', compact('roles', 'permissions', 'verticals','selectedRoles', 'selectedPermissions', 'title', 'mode','managerlist','servicelines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate()
    {
       
		$this->user->username = \Input::get( 'username' );
        $this->user->email = \Input::get( 'email' );
        $this->user->password = \Input::get( 'password' );
		if (\Input::get('mgrid') != 0)
		{
			$this->user->mgrid = \Input::get('mgrid');
		}else{
			$this->user->mgrid = NULL;
		}

		$servicelines = \Input::get('servicelines');
        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $this->user->password_confirmation = \Input::get( 'password_confirmation' );

        // Generate a random confirmation code
        $this->user->confirmation_code = md5(uniqid(mt_rand(), true));

        if (\Input::get('confirm')) {
            $this->user->confirmed = \Input::get('confirm');
        }

        // Permissions are currently tied to roles. Can't do this yet.
        //$user->permissions = $user->roles()->preparePermissionsForSave(\Input::get( 'permissions' ));

        // Save if valid. Password field will be hashed before save
        $this->user->save();

        if ( $this->user->id ) {
			$person = $this->person;
			$person->firstname = \Input::get('firstname');
			$person->lastname = \Input::get('lastname');
			$person->address = \Input::get('address');
			$person->phone = \Input::get('phone');
			$latLng = $this->geoCodeAddress($person->address);
			$person->lat = $latLng['lat'];
			$person->lng = $latLng['lng'];
			$person = $this->user->person()->save($person);
			$person->industryfocus()->attach(\Input::get('vertical'));
            //$person->industryfocus()->sync(\Input::get('vertical'));

			// set up tracking
			$track = new Track();
			$track->user_id =  $this->user->id ;
			$track->save();
            $this->user->saveRoles(\Input::get( 'roles' ));
			$this->user->serviceline()->attach(\Input::get('serviceline'));
			
            /*if (Config::get('confide::signup_email')) {
               
				$user = $this->user;
                Mail::queueOn(
                    Config::get('confide::email_queue'),
                    Config::get('confide::email_account_confirmation'),
                    compact('user'),
                    function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->username)
                            ->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
                    }
                );
            }*/

            // Redirect to the new user page
            return \Redirect::to('admin/users/')
                ->with('success', Lang::get('admin/users/messages.create.success'));

        } else {

            // Get validation errors (see Ardent package)
            $error = $this->user->errors()->all();

            return \Redirect::to('admin/users/create')
                ->withInput(\Input::except('password'))
                ->with( 'error', $error );
        }
    }



    /**
     * Display the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getShow($user)
    {
        // redirect to the frontend
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getEdit($user)
    {
      

	    if ( $user->id )
        {
            $user = $this->user->with('serviceline')->find($user->id);

            $roles = $this->role->all();
            $permissions = $this->permission->all();

            // Title
        	$title = Lang::get('admin/users/title.user_update');
        	// mode
        	$mode = 'edit';
			$managerlist = $this->getManagerList();
			// Allow the admin user to add themselves to any service line
			// Technically this is redundant as only admins can add / edit users
			if($user->id == \Auth::id()){
				$servicelines = Serviceline::pluck('ServiceLine','id');
			}else{
				$servicelines = Serviceline::whereIn('id',$this->userServiceLines)
				->pluck('ServiceLine','id');
			}

			$verticals = SearchFilter::where('searchcolumn','=','vertical')
			->where('type','!=','group')			
			->pluck('filter','id');
        	return view()->make('admin/users/create_edit', compact('user', 'roles', 'permissions', 'verticals','title', 'mode','managerlist','servicelines'));
        }
        else
        {
            return \Redirect::to('admin/users')->with('error', Lang::get('admin/users/messages.does_not_exist'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function postEdit($user)
    {

		$person = Person::where('user_id','=',$user->id)->first();

		
		$oldUser = clone $user;
		$user->username = \Input::get( 'username' );
		$user->email = \Input::get( 'email' );
		if (count($person)==0)
		{
			$person = $this->person;
			$person->user_id=$user->id;
			$person = $user->person()->save($person);
			$person->industryfocus()->attach(\Input::get('vertical'));
			
		}



		$user->person->address = \Input::get('address');
		$latLng = $this->geoCodeAddress($user->person->address);
		
		$user->person->lat = $latLng['latitude'];
		$user->person->lng = $latLng['longitude'];
		$user->person->firstname = \Input::get('firstname');
		$user->person->lastname = \Input::get('lastname');
		$user->person->phone = \Input::get('phone');
		$user->confirmed = \Input::get( 'confirm' );
		if (\Input::get('mgrid') != 0)
		{
			$user->mgrid = \Input::get('mgrid');
		}else{
			$user->mgrid = NULL;
		}
		//set update rules
		
		$rules = array('username' => 'required|alpha_num',
            	'email' => 'required|email');
		
		foreach ($rules as $key=>$value)
		{
			if($oldUser->$key != $user->$key)
			{
				$rules[$key] = $rules[$key]."|unique:users";
			}
			
		}
		
        $password = \Input::get( 'password' );
        $passwordConfirmation = \Input::get( 'password_confirmation' );

        if(! empty($password)) {
            if($password === $passwordConfirmation) {
                $user->password = $password;
                // The password confirmation will be removed from model
                // before saving. This field will be used in Ardent's
                // auto validation.
                $user->password_confirmation = $passwordConfirmation;
            } else {
                // Redirect to the new user page
                return \Redirect::to('admin/users/' . $user->id . '/edit')
                ->with('error', Lang::get('admin/users/messages.password_does_not_match'));
            }
        }
            
        if($user->confirmed == null) {
            $user->confirmed = $oldUser->confirmed;
        }

        if ($user->save($rules)) {
            // Save roles. Handles updating.
            $user->saveRoles(\Input::get( 'roles' ));
			$user->person->save();
        } else {
			
            return \Redirect::to('admin/users/' . $user->id . '/edit')
                ->with('error', Lang::get('admin/users/messages.edit.error'));
        }

        // Get validation errors (see Ardent package)
        $error = $user->errors()->all();

        if(empty($error)) {
            // Redirect to the new user page
            $user->serviceline()->sync(\Input::get('serviceline'));
            $person->industryfocus()->sync(\Input::get('vertical'));
            return \Redirect::to('admin/users/')->with('success', Lang::get('admin/users/messages.edit.success'));
        } else {
            return \Redirect::to('admin/users/' . $user->id . '/edit')->with('error', Lang::get('admin/users/messages.edit.error'));
        }

    }

    /**
     * Remove user page.
     *
     * @param $user
     * @return Response
     */
    public function getDelete($user)
    {
        // Title
        $title = Lang::get('admin/users/title.user_delete');

        // Show the page
        return view()->make('admin/users/delete', compact('user', 'title'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param $user
     * @return Response
     */
    public function postDelete($user)
    {
        
		
		// Check if we are not trying to delete ourselves
        if ($user->id === Confide::user()->id)
        {
            // Redirect to the user management page
            return \Redirect::to('admin/users')
            ->with('error', Lang::get('admin/users/messages.delete.impossible'));
        }

        AssignedRoles::where('user_id', $user->id)->delete();

        
        $user->delete();
		return \Redirect::to('admin/users')
		->with('success', Lang::get('admin/users/messages.delete.success'));
       
    }
	
	
	public function import()
	{
		$servicelines = Serviceline::whereIn('id',$this->userServiceLines)
				->pluck('ServiceLine','id');
		return view()->make('admin/users/import',compact('servicelines'));
		
	}

   public function bulkImport()
	{
		$rules= ['upload' => 'required'];
   		
		// Make sure we have a file
		$validator = Validator::make(\Input::all(), $rules);

    	if ($validator->fails())
		{
			
			return \Redirect::back()
			->withErrors($validator);
		}

		// Make sure its a CSV file - test #1
		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
		if(!in_array($_FILES['upload']['type'],$mimes)){
			
		 	return \Redirect::back()->withErrors(['Only CSV files are allowed']);
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
			
			return \Redirect::back()->withErrors(['Invalid file format.  Check the fields: ']);
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

		return \Redirect::to('/admin/users');
		
	}
	
	private function executeQuery($query)
	{
		
		$results = \DB::statement($query);
		echo $query . ";<br />";
	}
	private function rawQuery($query,$error,$type){
		$result = array();
		try{
			switch ($type) {
				case 'insert':
					$result = \DB::insert( \DB::raw($query ) );
					break;
				case 'select':
					$result = \DB::select( \DB::raw($query ) );
				break;
				
				case 'update':
					$result = \DB::select( \DB::raw($query ) );
				break;
			
				default:
					$result = \DB::select( \DB::raw($query ) );
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
		return Response::make(rtrim($export['output'], "\n"), 200, $export['headers']);
	}
	
	
	
	private function getManagerList()
	{
		$managerroles=['3','4'];
		$managers = $this->user->whereHas('roles', 
			function($q) use($managerroles){
			$q->whereIn('role_id',$managerroles);
			})->with('person')->get();
		$managerlist[0] ='';
		foreach($managers as $manager){
			$managerlist[$manager->id] = $manager->person->lastname . ",". $manager->person->firstname;
		}
		asort($managerlist);
		return $managerlist;	
	}
private function geoCodeAddress($address)
	{
		

		return  $this->getLatLng($address);

			
	}
	
	private function getLatLng($address)
	{
		try {
			
		$geocode = Geocoder::geocode($address);
		// The GoogleMapsProvider will return a result
		return $geocode;
		
		} catch (\Exception $e) {
			// No exception will be thrown here
			//echo $e->getMessage();
		}
		
	}
	
}
