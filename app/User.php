<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Crypt;
use Carbon\Carbon;


class User extends Authenticatable
{
 use Notifiable,HasRoles, Geocode, SearchableTrait,SoftDeletes;


 protected $expiration = '2880';

 protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            
            'persons.lastname' => 10,
            'persons.firstname' => 10,
            'users.email' => 10,
            'users.employee_id'=>5,
           
          
        ],
        'joins' => [
            'persons' => ['users.id','persons.user_id'],
        ],
    ];
    

    public function canImpersonate()
    {
        return $this->hasRole('admin');
    }
    

    public function canBeImpersonated()
    {
        return  ! $this->hasRole(['admin','sales_operations']);
    }

    public $fillable = [
                'email',
                'lastlogin',
                'confirmed',
                'confirmation_code',
                'employee_id',
                'api_token',
                'avatar'];
    /**
     * Get user by username
     * @param $username
     * @return mixed
     */

    public $dates=['lastlogin','created_at','updated_at','deleted_at','nonews'];

     public function person()
     {
          return $this->hasOne(Person::class,'user_id')->orderBy('lastname','firstname')
          ->withDefault('No longer with the company');
     }

    public function fullName()
    {
        if($this->person){
            return $this->person->postName();
        }else{
            return null;
        }
    }

    public function personWithOutGeo(){
        return $this->hasOne(Person::class,'user_id');
    }

     public function usage()
     {
          return $this->hasMany(Track::class,'user_id');
     }

    public function scopeFirstLogin($query, Carbon $date){

    // this doesnt make sense
    return $query->whereHas('usage',function ($q) use ($date){
        $q->where('roles.id','=',$role);
        });
    }

    public function firstLogin(){
        return $this->hasMany(Track::class,'user_id');
    }

    public function active(){
        return $this->where('confirmed','=',1);
    }
    
    public function activities(){
        return $this->hasMany(Activity::class);
    }


    public function manager() 
    {

        return $this->belongsTo(User::class,'mgrid','id');
    }

    /**
    * Rank documents
    *
    */

    public function rankings()
    {
        return $this->belongsToMany(Document::class)->withPivot('rank');
    }

    public function latestlogin()
    {
        return $this->hasMany(Track::class)->max('lastactivity');
    }

    public function reports() 
    {

        return $this->hasMany(User::class,'id','mgrid');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function serviceline ()
    {
        return $this->belongsToMany(Serviceline::class)->withTimestamps();

    }


    public function watching () {
        return $this->belongsToMany(Address::class,'location_user');

    }
      

    /*public function getUserByUsername( $username )
    {
        return $this->where('username', '=', $username)->first();
    }
    */
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

    public function setAccess(){
        return $this->api_token ."tbmm".Crypt::encrypt(now());
    }

    public function getAccess($id){
        if( Crypt::decrypt(substr($id,strpos($id,'tbmm')+4,strlen($id)))->diffInMinutes() < $this->expiration){

        return $this->where('api_token','=',substr($id,0,strpos($id,'tbmm')))->first();
    }else{
        return false;
        }
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
        return $this->roles->pluck('id')->toArray();
        /*$roleIds = false;
        if( !empty( $roles ) ) {
            $roleIds = array();
            foreach( $roles as &$role )
            {
                $roleIds[] = $role->id;
            }
        }
        return $roleIds;*/
    }

     /**
     * Returns user's current role ids only.
     * @return array|bool
     */
    public function currentServiceLineIds()
    {
        return $this->serviceline->pluck('id')->toArray();
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

    public function setApiToken(){

        return $this->api_token = md5(uniqid(mt_rand(), true));
        

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


    

    /**
   * scopeLastLogin Select last login of user]
   * @param  QueryBuilder $query    [description]
   * @param  Array $interval intervale['from','to']
   * @return QueryBuilder          [description]
   */

    public function scopeUpcomingActivities($query,$nextdays)
    {
       
        return $query->with(['activities',function ($q) use($nextdays){
                $q->where('activity_date','>',now())
                ->where('activity_date','<=',Carbon::now()->addDays($nextdays));
            }]);

    }


}