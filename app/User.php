<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,HasRoles, Geocode;
	



	public $fillable = ['username','email','lastlogin','confirmed','confirmation_code','employee_id'];
    /**
     * Get user by username
     * @param $username
     * @return mixed
     */

    public $dates=['lastlogin','created_at','updated_at','nonews'];

	 public function person()
	 {
		  return $this->hasOne(Person::class,'user_id')->orderBy('lastname','firstname');
	 }
	 
	 public function fullName(){
	 	return $this->person->postName();
	 }
	 public function usage()
	 {
		  return $this->hasOne(Track::class,'user_id');
	 }
	 
	 public function watching () {
			return $this->belongsToMany(Location::class); 
		 
	 }

	 public function serviceline () {
			return $this->belongsToMany(Serviceline::class)->withTimestamps(); 
		 
	 }

   public function roles()
	{
		return $this->belongsToMany(Role::class);
	}

	
	  
	  public function manager() {
		  
		  return $this->belongsTo(User::class,'mgrid','id');
	  }
	  
	  public function reports() {
		  
		   return $this->hasMany(User::class,'id','mgrid');
	  }
   
    public function getUserByUsername( $username )
    {
        return $this->where('username', '=', $username)->first();
    }

    /**
     * Get the date the user was created.
     *
     * @return string
     */
    public function joined()
    {
        return \String::date(\Carbon::createFromFormat('Y-n-j G:i:s', $this->created_at));
    }
	/**
     * Rank documents
     *
     */

	public function rankings()
	{
		return $this->belongsToMany(Document::class)->withPivot('rank');
	}

    /**
     * Save roles inputted from multiselect
     * @param $inputRoles
     */
    public function saveRoles($inputRoles)
    {
        if(! empty($inputRoles)) {
            $this->roles()->sync($inputRoles);
        } else {
            $this->roles()->detach();
        }
    }

    /**
     * Returns user's current role ids only.
     * @return array|bool
     */
    public function currentRoleIds()
    {
        $roles = $this->roles;
        $roleIds = false;
        if( !empty( $roles ) ) {
            $roleIds = array();
            foreach( $roles as &$role )
            {
                $roleIds[] = $role->id;
            }
        }
        return $roleIds;
    }

    /**
     * Redirect after auth.
     * If ifValid is set to true it will redirect a logged in user.
     * @param $redirect
     * @param bool $ifValid
     * @return mixed
     */
    public static function checkAuthAndRedirect($redirect, $ifValid=false)
    {
        // Get the user information
        $user = \Auth::user();
        $redirectTo = false;

        if(empty($user->id) && ! $ifValid) // Not logged in redirect, set session.
        {
            \Session::put('loginRedirect', $redirect);
            $redirectTo = \Redirect::to('user/login')
                ->with( 'notice', \Lang::get('user/user.login_first') );
        }
        elseif(!empty($user->id) && $ifValid) // Valid user, we want to redirect.
        {
            $redirectTo = \Redirect::to($redirect);
        }

        return array($user, $redirectTo);
    }

    public function currentUser()
    {
        return (new Confide(new ConfideEloquentRepository()))->user();
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }
	
	/**
     * Bulk import csv file of users.
     * @param $path
     * @param $filename
	 * @param $table
	 * @param $fields
     * @return string
     */

	public function _import_csv($filename, $table,$fields)
	{
		$filename = str_replace("\\","/",$filename);

	$query = sprintf("LOAD DATA INFILE '".$filename."' INTO TABLE ". $table." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$fields.")", $filename);
	
	
	
	try {
		$result = \DB::connection()->getpdo()->exec($query);
		return $result;
	}
	catch (\Exception $e)
		{
		 throw new \Exception( 'Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
		
		}
	
	}
	/**
     * Bulk export csv file of users.
	 * @param $fields
	 * @param $data
     * @return string
     */
	
	
	
	public function export ($data) {
		
		$filename = "attachment; filename=\"". time() . '-' ."users.csv\"";
		$fields=['id',['person'=>'firstname'],['person'=>'lastname'],'email','lastlogin',['serviceline'=>'ServiceLine']];

		//$data = $this->user->with('person')->get();
		
	$output="";
		
		foreach ($fields as $field) {
			
			if(! is_array($field)){
			 $output.=$field.",";
			}else{
				
				$output.= $field[key($field)].",";	
			}
				
		}
		 $output.="\n";
		  foreach ($data as $row) {
			  
			  reset ($fields);
			  foreach ($fields as $field) {
				if(! is_array($field)){
					if(! $row->$field) {
						$output.=",";
					}else{
						
				  		$output.=str_replace(","," ",strip_tags($row->$field)).",";
						
					}
				}else{
					$key = key($field);
					$element = $field[key($field)];
					
					if(! isset($row->$key->$element)) {
						$output.=",";
					}else{
				  		$output.=str_replace(","," ",strip_tags($row->$key->$element)).",";
						
					}
					
					
				}

				  
			  }
			  $output.="\n";
			  
			  
		  }
			$export['output'] = $output;
		  $export['headers'] = array(
			  'Content-Type' => 'text/csv',
			  'Content-Disposition' => $filename ,
		  );
	
	return $export;

 	 //return Response::make(rtrim($output, "\n"), 200, $headers);
	
	
	}

	public function seeder(){
		$this->api_token =\Hash::make(str_random(60));
		$this->save();
	}
}
