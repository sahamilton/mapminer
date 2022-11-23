<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AddressPerson extends Model
{
    public $table = 'address_person';
    public $timestamps = true;
    public $fillable = ['person_id', 'address_id', 'status_id', 'created_at', 'updated_at', 'ranking'];
    /**
     * [orders description].
     *
     * @return [type] [description]
     */

    /**
     * [branch description].
     *
     * @return [type] [description]
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    /**
     * [address description].
     *
     * @return [type] [description]
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    /**
     * [activities description].
     *
     * @return [type] [description]
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'address_id', 'address_id');
    }

    /**
     * [opportunities description].
     *
     * @return [type] [description]
     */
    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'address_branch_id', 'id');
    }

    /**
     * [scopeActivityChart description].
     *
     * @param [type] $query [description]
     *
     * @return [type]        [description]
     */
    public function scopeActivityChart($query)
    {
        return $query->selectRaw(
            'address_person.person_id as person_id,
            YEARWEEK(activities.expected_close,3) as yearweek,
            sum(activities.value) as funnel'
        )
            ->groupBy(['person_id', 'yearweek'])
            ->orderBy('yearweek', 'asc');
    }

    /**
     * [scopeOpenOpportunities description].
     *
     * @param [type] $query [description]
     *
     * @return [type]        [description]
     */
    public function scopeOpenOpportunities($query)
    {
        $this->opportunities()->where('closed', 0);
    }

    /**
     * [scopeWonOpportunities description].
     *
     * @param [type] $query [description]
     *
     * @return [type]        [description]
     */
    public function scopeWonOpportunities($query)
    {
        $this->opportunities()->where('closed', 1);
    }

    /**
     * [scopeLostOpportunities description].
     *
     * @param [type] $query [description]
     *
     * @return [type]        [description]
     */
    public function scopeLostOpportunities($query)
    {
        $this->opportunities()->where('closed', 2);
    }

    public function leadsource()
    {
        return $this->hasManyThrough(LeadSource::class, Address::class, 'id', 'id', 'address_id', 'lead_source_id');
    }

    /**
     * [scopeStaleLeads description].
     *
     * @param [type] $query      [description]
     * @param [type] $leadsource [description]
     * @param [type] $branches   [description]
     * @param [type] $before     [description]
     *
     * @return [type]             [description]
     */
    public function scopeStaleLeads(
        $query,
        array $leadsource,
        array $person,
        Carbon $before
    ) {
        return $query
            ->whereHas(
                'leadsource', function ($q) use ($leadsource) {
                    $q->whereIn('leadsources.id', $leadsource);
                }
            )
            ->whereIn('person_id', $person)
            ->where('created_at', '<=', $before)
            ->doesntHave('activities')
            ->doesntHave('opportunities');
    }
}
