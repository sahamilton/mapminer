<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Crypt;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable; 
use Illuminate\Support\Arr;


class User extends Authenticatable implements Auditable
{
    use Notifiable,HasRoles, Geocode, SearchableTrait, SoftDeletes, \OwenIt\Auditing\Auditable;


    const EXPIRATION = 2880;

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
    protected $hidden = ['created_at','updated_at','deleted_at','password'];
    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'lastlogin',
        'updated_at',
        'confirmed', 
        'remember_token',  
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored', 
        'pivotAttached',
        'pivotDetached',
        'pivotUpdated'
    ];

    public function transformAudit(array $data): array
    {
        if (Arr::has($data, 'new_values.role_id')) {
            $data['old_values']['role_name'] = Role::find($this->getOriginal('role_id'))->dislay_name;
            $data['new_values']['role_name'] = Role::find($this->getAttribute('role_id'))->display_name;
        }

        return $data;
    }

    

    /**
     * [canImpersonate description]
     * 
     * @return [type] [description]
     */
    public function canImpersonate()
    {
        return $this->hasRole('admin');
    }
    
    /**
     * [canBeImpersonated description]
     * 
     * @return [type] [description]
     */
    public function canBeImpersonated()
    {
        return  ! $this->hasRole(['admin']);
    }

    public $fillable = [
                'email',
                'lastlogin',
                'confirmed',
                'confirmation_code',
                'employee_id',
                'api_token',];
    /**
     * Get user by username
     * 
     * @param $username
     * 
     * @return mixed
     */
    public function getCreationDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }
    public $dates=['lastlogin','created_at','updated_at','deleted_at','nonews'];
    /**
     * [person description]
     * 
     * @return [type] [description]
     */
    public function person()
    {
        return $this->hasOne(Person::class, 'user_id')
            ->orderBy('lastname', 'asc')
            ->orderBy('firstname', 'asc')
            ->withDefault('No longer with the company');
    }
    /**
     * [deletedperson description]
     * 
     * @return [type] [description]
     */
    public function deletedperson()
    {
        return $this->hasOne(Person::class, 'user_id')->onlyTrashed()
            ->withDefault('No longer with the company');
    }
    /**
     * [fullName description]
     * 
     * @return [type] [description]
     */
    public function fullName()
    {
        if ($this->person) {
            return $this->person->fullName();
        } else {
            return null;
        }
    }

    /**
     * [postName description]
     * 
     * @return [type] [description]
     */
    public function postName()
    {
        if ($this->person) {
            return $this->person->postName();
        } else {
            return null;
        }
    }

    
    /**
     * [personWithOutGeo description]
     * 
     * @return [type] [description]
     */
    public function personWithOutGeo()
    {
        return $this->hasOne(Person::class, 'user_id');
    }
    /**
     * [usage description]
     * 
     * @return [type] [description]
     */
    public function usage()
    {
          return $this->hasMany(Track::class, 'user_id')->whereNotNull('lastactivity');
    }
    /**
     * [scopeFirstLogin description]
     * 
     * @param [type] $query [description]
     * @param Carbon $date  [description]
     * 
     * @return [type]        [description]
     */
    public function scopeFirstLogin($query, Carbon $date)
    {

        return $query->whereHas(
            'usage', function ($q) use ($date) {
                $q->where('roles.id', '=', $role);
            }
        );
    }
    /**
     * [firstLogin description]
     * 
     * @return [type] [description]
     */
    public function firstLogin()
    {
        return $this->hasMany(Track::class, 'user_id');
    }
    /**
     * [active description]
     * 
     * @return [type] [description]
     */
    public function active()
    {
        return $this->where('confirmed', '=', 1);
    }
    /**
     * [activities description]
     * 
     * @return [type] [description]
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * [manager description]
     * 
     * @return [type] [description]
     */
    public function manager() 
    {

        return $this->belongsTo(User::class, 'mgrid', 'id');
    }

    /**
     * Rank documents
     *
     * @return [type] [<description>]
     */
    public function rankings()
    {
        return $this->belongsToMany(Document::class)->withPivot('rank');
    }
    /**
     * [latestlogin description]
     * 
     * @return [type] [description]
     */
    public function latestlogin()
    {
        return $this->hasMany(Track::class)->max('lastactivity');
    }
    /**
     * [reports description]
     * 
     * @return [type] [description]
     */
    public function reports() 
    {

        return $this->hasMany(User::class, 'id', 'mgrid');
    }

    public function oracleMatch()
    {
        return $this->hasOne(Oracle::class, 'person_number', 'employee_id');
    }

    public function oracleTeam()
    {
        
        return $this->hasMany(Oracle::class, 'manager_email_address', 'email');
    }
    /**
     * [roles description]
     * 
     * @return [type] [description]
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    /**
     * [serviceline description]
     * 
     * @return [type] [description]
     */
    public function serviceline()
    {
        return $this->belongsToMany(Serviceline::class)->withTimestamps();

    }
    public function scheduledReports()
    {
        return $this->belongsToMany(Report::class, 'report_distribution')->withTimestamps()->orderBy('report');
    }
    /**
     * [watching description]
     * 
     * @return [type] [description]
     */
    public function watching() 
    {
        return $this->belongsToMany(Address::class, 'location_user');

    }
    /**
     * [scopeSearch description]
     * 
     * @param [type] $query  [description]
     * @param [type] $search [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSearch($query, $search)
    { 
        
        return  $query->where('users.email', 'like', "%{$search}%");
     

    }

    /**
     * [position description]
     * 
     * @return [type] [description]
     */
    public function position()
    {
        $position = $this->person()
            ->select('lat', 'lng')
            ->whereNotNull('lat')
            ->first();
        
        if ($position) {
                return implode(",", $position->toArray());
        }
        return "39.50,98.35";
    }
    /**
     * [setAccess description]
     *
     * @return [<description>]
     */
    public function setAccess()
    {
        return $this->api_token ."tbmm".Crypt::encrypt(now());
    }
    /**
     * [getAccess description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function getAccess($token)
    {
        
        if (Crypt::decrypt(substr($token, strpos($token, 'tbmm')+4, strlen($token)))->diffInMinutes() < self::EXPIRATION) {

            return $this->where('api_token', '=', substr($token, 0, strpos($token, 'tbmm')))->first();
        } else {
            return false;
        }
    }


    public function getExpiration($token)
    {
        return Crypt::decrypt(substr($token, strpos($token, 'tbmm')+4, strlen($token)))->addMinutes(self::EXPIRATION);

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
        if (! empty($inputRoles)) {
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
     * 
     * @return array|bool
     */
    public function currentServiceLineIds()
    {
        if ($this->hasRole(['admin'])) {
            return Serviceline::orderBy('ServiceLine')->pluck('ServiceLine', 'id')->toArray();
        }
        return $this->serviceline()->orderBy('ServiceLine')->pluck('ServiceLine', 'servicelines.id')->toArray();
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

        if (empty($user->id) && ! $ifValid) // Not logged in redirect, set session.
        {
            \Session::put('loginRedirect', $redirect);
            $redirectTo = \Redirect::to('user/login')
                ->with('notice', \Lang::get('user/user.login_first'));
        } elseif (!empty($user->id) && $ifValid) {
        // Valid user, we want to redirect.
        
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

    public function getFormattedEmail()
    {
        return ['email'=>$this->email, 'name'=>$this->person->fullName()];
    }


    /**
     * [seeder Seed api_token for all users. Not used]
     * @return [type] [description]
     */

    public function setApiToken()
    {

        return $this->api_token = md5(uniqid(mt_rand(), true));
        

    }
    /**
     * scopeWithRole Select User by role
     * @param  QueryBuilder $query [description]
     * @param  int $role  Role id
     * @return QueryBuilder        [description]
     */
    public function scopeWithRole($query,$role) 
    {
        return $query->whereHas(
            'roles', function ($q) use ($role) {
                $q->where('roles.id', '=', $role);
            }
        );
    }

    /**
     * ScopeLastLogin Select last login of user]
     * 
     * @param QueryBuilder $query    [description]
     * @param Array        $interval interval['from','to']
     * 
     * @return QueryBuilder [description]
     */
    public function scopeLastLogin($query, $interval=null)
    {
        if ($interval) {
            return $query->whereBetween('lastlogin', [$interval['from'],$interval['to']]);
        }
        return $query->whereNull('lastlogin');
    }
    public function scopeWithLastLoginId($query)
    {
        return $query->select('users.*')
            ->selectSub('select id as last_login_id from track where user_id = users.id and lastactivity is not null order by track.created_at desc limit 1', 'last_login_id');
       
    }
    public function lastLogin()
    {
        return $this->belongsTo(Track::class);
    }
    /**
     * [scopeUpcomingActivities description]
     * 
     * @param [type] $query    [description]
     * @param [type] $nextdays [description]
     * 
     * @return [type]           [description]
     */
    public function scopeUpcomingActivities($query,$nextdays)
    {
       
        return $query->with(
            ['activities',function ($q) use ($nextdays) {
                $q->where('activity_date', '>', now())
                    ->where('activity_date', '<=', Carbon::now()->addDays($nextdays));
            }]
        );

    }

    public function scopeTotalLogins($query, $period=null)
    {
        return $query->withCount(
            ['usage'=>function ($q) use ($period) {
                $q->when(
                    $period, function ($q1) use ($period) {
                        $q1->whereBetween('created_at', [$period['from'], $period['to']]);
                    }
                );
            }
            ]
        );
        
    }


}
