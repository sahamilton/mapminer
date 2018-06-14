<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Nicolaslopezj\Searchable\SearchableTrait;


class User extends Authenticatable
{
 use Notifiable,HasRoles, Geocode, SearchableTrait;

 protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'users.username' => 10,
            'persons.lastname' => 10,
            'persons.firstname' => 10,
            'users.email' => 10,
            'users.employee_id'=>5,
           
          
        ],
        'joins' => [
            'persons' => ['users.id','persons.user_id'],
        ],
    ];



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
     public function scopeFirstLogin($query, Carbon $date){
    

    return $query->whereHas('usage',function ($q) use ($date){
        $q->where('roles.id','=',$role);
        });
     }

	 public function firstLogin(){
	 	return $this->hasMany(Track::class,'user_id');
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

	public function active(){
		return $this->where('confirmed','=',1);
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

    public function position(){
        $position = $this->person()
                ->select('lat','lng')
                ->whereNotNull('lat')
                ->first();
        
        if($position){
                return implode(",",$position->toArray());
        }
        return "39.50,98.35";
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
 * [seeder Seed api_token for all users. Not used]
 * @return [type] [description]
 */
	public function seeder(){
		$this->api_token =\Hash::make(str_random(60));
		$this->save();
	}
/**
 * scopeWithRole Select User by role
 * @param  QueryBuilder $query [description]
 * @param  int $role  Role id
 * @return QueryBuilder        [description]
 */
  public function scopeWithRole($query,$role){
    return $query->whereHas('roles',function ($q) use ($role){
      $q->where('roles.id','=',$role);
    });
  }
  /**
   * scopeLastLogin Select last login of user]
   * @param  QueryBuilder $query    [description]
   * @param  Array $interval intervale['from','to']
   * @return QueryBuilder          [description]
   */

  public function scopeLastLogin($query,$interval=null){

		if($interval){
				return $query->whereBetween('lastlogin',[$interval['from'],$interval['to']]);
			}
		return $query->whereNull('lastlogin');
	}
}
