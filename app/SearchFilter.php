<?php

namespace App;

/**
* SearchFilter
*/
class SearchFilter extends NodeModel
{

  /**
   * Table name.
   *
   * @var string
   */
    protected $table = 'searchfilters';

    public $tables = ['companies', 'locations'];

    public $rules = [
         'filter' => 'required',
         'type' =>'required',
    ];

    // Don't forget to fill this array
    protected $fillable = ['filter', 'type', 'searchtable', 'searchcolumn', 'canbenull', 'inactive', 'color'];

    /**
     * [setSearch description].
     *
     * @param [type] $search [description]
     */
    public function setSearch($search = null)
    {
        $searchFilter = [];
        $searchFilters = [];

        if (isset($search)) {
            $keys = array_keys($search);
        } else {
            if (\Session::has('Search')) {
                $keys = array_keys($search);
            } else {
                // If session isn't set and none requested then get all filters
                $keys = $this->whereNotNull('type')
                    ->orderBy('searchtable', 'asc')
                    ->orderBy('searchcolumn', 'asc')
                    ->orderBy('lft', 'asc')
                    ->pluck('id');
            }
        }

        $filters = $this->whereIn('id', $keys)->whereNotNull('type')->orderBy('lft', 'asc')->get();
        foreach ($filters as $filter) {
            // we need to set the vertical parent to checked if depth > 2
            if ($filter->isLeaf() && $filter->depth > 2 && $filter->inactive == 0) {
                // set the parent to the be checked
                $parent = $filter->parent()->get();
                if (! isset($searchFilter[$parent[0]['searchtable']][$parent[0]['searchcolumn']][$parent[0]['id']])) {
                    $searchFilter[$parent[0]['searchtable']][$parent[0]['searchcolumn']][$parent[0]['id']] = $parent[0]['id'];
                }
                $searchFilter[$filter->searchtable][$filter->searchcolumn][$filter->id] = $filter->id;
            }
            // We dont want to set it twice!
            if (! isset($searchFilter[$filter->searchtable][$filter->searchcolumn][$filter->id])) {
                $searchFilter[$filter->searchtable][$filter->searchcolumn][$filter->id] = $filter->id;
            }
        }

        \Session::forget('Search');
        \Session::put('Search', [$searchFilter]);
    }

    /**
     * [segments description].
     *
     * @return [type] [description]
     */
    public function segments()
    {
        return $this->where('searchColumn', '=', 'segment')
            ->where('canbenull', '=', 0)
            ->where('type', '!=', 'group')
            ->orderBy('filter', 'asc')
            ->pluck('filter', 'id')
            ->toArray();
    }

    /**
     * [vertical description].
     *
     * @return [type] [description]
     */
    public function vertical()
    {
        return $this->where('searchColumn', '=', 'vertical')
            ->where('canbenull', '=', 0)
            ->where('type', '!=', 'group')
            ->orderBy('filter', 'asc')
            ->pluck('filter', 'id')
            ->toArray();
    }

    /**
     * [industrysegments description].
     *
     * @return [type] [description]
     */
    public function industrysegments()
    {
        $filters = $this->first();

        return $filters->getDescendants()
            ->where('searchtable', '=', 'companies')

            ->where('inactive', '=', 0);
    }

    /**
     * [companies description].
     *
     * @return [type] [description]
     */
    public function companies()
    {
        return $this->hasMany(Company::class, 'vertical', 'id');
    }

    /**
     * [leads description].
     *
     * @return [type] [description]
     */
    public function leads()
    {
        return $this->hasMany(Address::class, 'vertical', 'id');
    }

    /**
     * [people description].
     *
     * @return [type] [description]
     */
    public function people()
    {
        return $this->belongsToMany(Person::class, 'person_search_filter', 'search_filter_id')
            ->withTimestamps();
    }

    /**
     * [campaigns description].
     *
     * @return [type] [description]
     */
    public function campaigns()
    {
        return $this->belongsToMany(Salesactivity::class, 'activity_process_vertical', 'vertical_id', 'activity_id')
            ->groupBy(['vertical_id', 'activity_id'])
            ->where('datefrom', '<=', date('Y-m-d'))
            ->where('dateto', '>=', date('Y-m-d'))
            ->withPivot('salesprocess_id');
    }

    /**
     * [segment description].
     *
     * @return [type] [description]
     */
    public function segment()
    {
        return $this->hasMany(Location::class, 'segment')->count();
    }

    /**
     * [locations description].
     *
     * @return [type] [description]
     */
    public function locations()
    {
        $count = 0;
        $companies = Company::where('vertical', '=', $this->id)->get();

        foreach ($companies as $company) {
            $count = $count + $company->locations()->count();
        }

        return $count;
    }
}
