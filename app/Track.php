<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    // Add your validation rules here
    public static $rules;
    protected $table = 'track';
    // Don't forget to fill this array
    public $fillable = ['user_id', 'lastactivity'];
    public $dates = ['lastactivity'];
    public $errors;

    /**
     * [scopeLastLogin description].
     *
     * @param [type] $query    [description]
     * @param [type] $interval [description]
     *
     * @return [type]           [description]
     */
    public function scopeLastLogin($query, $interval = null)
    {
        $sub = $query->selectRaw('`user_id`,max(`lastactivity`) as `lastlogin`')
            ->groupBy('user_id');
        // this should be a join
        $lastlogin = $query->join("({$sub->toSql()}) as max")
            ->mergeBindings($sub->getQuery());
        if ($interval) {
            return $lastlogin->whereBetween('max.lastlogin', $interval);
        }

        return $lastlogin->whereNull('max.laslogin');
    }

    /**
     * [user description].
     *
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * [getLogins description].
     *
     * @param array|null $users [description]
     *
     * @return [type]            [description]
     */
    public function getLogins(array $users = null)
    {
        if ($users) {
            $subQuery = ($this->whereHas(
                'user', function ($q) {
                    $q->where('confirmed', '=', 1);
                }
            )
            ->whereIn('user_id', $users)
            ->selectRaw(
                'count(user_id) as logins,
                date(min(`lastactivity`)) as datelabel,
                DATE_FORMAT(min(`lastactivity`),"%Y-%m") as firstlogin'
            )
            ->whereNotNull('lastactivity')
            ->groupBy('user_id'));
        } else {
            $subQuery = ($this->whereHas(
                'user', function ($q) {
                    $q->where('confirmed', '=', 1);
                }
            )
            ->selectRaw(
                'count(user_id) as logins,
                date(min(`lastactivity`)) as datelabel,
                DATE_FORMAT(min(`lastactivity`),"%Y-%m") as firstlogin'
            )
            ->whereNotNull('lastactivity')
            ->groupBy('user_id'));
        }

        return  \DB::
                table(\DB::raw('('.$subQuery->toSql().') as ol'))
                ->selectRaw('count(logins) as logins,firstlogin')
                ->mergeBindings($subQuery->getQuery())
                ->groupBy('firstlogin')
                ->oldest('firstlogin')
                ->get();
    }
}
