<?php
namespace App;

use Nicolaslopezj\Searchable\SearchableTrait;

class Company extends NodeModel
{
    use Filters,SearchableTrait, Geocode;
    // Add your validation rules here
    public static $rules = [
         'companyname' => 'required',
         'serviceline'=>'required',
         'accounttypes_id'=>'required',
    ];
    public $limit = 2000;
    public $period;
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            
            'companyname' => 20,
            'customer_id' =>20.
            
            
           
          
        ],
       
    ];

    // Don't forget to fill this array
    protected $fillable = [
                'companyname', 
                'vertical',
                'person_id',
                'customer_id',
                'parent_id',
                'accounttypes_id'
            ];
    /**
     * [type description]
     * 
     * @return [type] [description]
     */
    public function assigned()
    {
        return $this->hasMany(Address::class)->whereHas('assignedToBranch');
    }

    public function type()
    {
        return $this->belongsTo(AccountType::class, 'accounttypes_id');
    }
    /**
     * [locations description]
     * 
     * @return [type] [description]
     */
    public function locations()
    {
                                
            return $this->hasMany(Address::class);
    }

    
    public function unassigned()
    {
        return $this->hasMany(Address::class)->whereDoesntHave('assignedToBranch');
    }

    public function opportunities()
    {
        return $this->hasManyThrough(
            Opportunity::class,
            Address::class,
            'company_id', // Foreign key on users table...
            'address_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
        );
    }
    /**
     * [stateLocations description]
     * 
     * @param  [type] $state [description]
     * 
     * @return [type]        [description]
     */
    public function stateLocations($state)
    {
            return $this->hasMany(Address::class)->where('state', '=', $state);
    }
    /**
     * [countlocations description]
     * 
     * @return [type] [description]
     */
    public function countlocations()
    {

        return $this->hasMany(Address::class)
            ->selectRaw('company_id,count(*) as count')
            ->groupBy('company_id');
    }
    /**
     * [locationcount description]
     * 
     * @return [type] [description]
     */
    public function locationcount()
    {

        return $this->hasMany(Address::class)
            ->selectRaw('company_id,count(*) as count')
            ->groupBy('company_id')
            ->first();
    }

    /**
     * [managedBy description]
     * 
     * @return [type] [description]
     */
    public function managedBy()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
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
    /**
     * [industryVertical description]
     * 
     * @return [type] [description]
     */
    public function industryVertical()
    {
        return $this->hasOne(SearchFilter::class, 'id', 'vertical');
    }
    /**
     * [salesNotes description]
     * 
     * @return [type] [description]
     */
    public function salesNotes()
    {
        return $this->belongsToMany(Howtofield::class)->withPivot('fieldvalue')->where('active', 1);
    }
    
    /**
     * [getFilteredLocations description]
     * 
     * @param [type] $filtered [description]
     * @param [type] $keys     [description]
     * @param [type] $query    [description]
     * @param [type] $paginate [description]
     * 
     * @return [type]           [description]
     */
    public function getFilteredLocations($filtered, $keys, $query, $paginate = null)
    {
        
        $columns = ['segment','businesstype'];
        //note we turned off business type.  When ready add it back into the array
        
        
        $isNullable = $this->isNullable($keys, $columns);


        return $query->get();
    }

    /**
     * [checkCompanyServiceLine description]
     * 
     * @param [type] $company_id       [description]
     * @param [type] $userServiceLines [description]
     * 
     * @return [type]                   [description]
     */
    public function checkCompanyServiceLine($company_id,$userServiceLines)
    {

        return $this->whereHas(
            'serviceline', function ($q) use ($userServiceLines) {
                 $q->whereIn('serviceline_id', $userServiceLines);

            }
        )->with('industryVertical')
        ->find($company_id);
    }
    /**
     * [getAllCompanies description]
     * 
     * @param [type] $filtered [description]
     * 
     * @return [type]           [description]
     */
    public function getAllCompanies($filtered=null)
    {

        $keys=array();
        $companies = $this->with(
            'managedBy', 'managedBy.userdetails', 
            'industryVertical', 'serviceline', 
            'countlocations'
        )
            ->withCount('locations');
            
        if ($filtered) {
            $keys = $this->getSearchKeys(['companies'], ['vertical']);
            $isNullable = $this->isNullable($keys, null);
            $companies = $companies->whereIn('vertical', $keys);

            if ($isNullable == 'Yes') {
                    $companies = $companies->orWhere( 
                        function ($query) use ($keys) {
                            $query->whereNull('vertical');
                        }
                    );
            }
        }

        return $companies->orderBy('companyname');
    }
    /**
     * [limitLocations description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    public function limitLocations($location)
    {
        if ($this->locations->count() > $this->limit) {
            $locations = Address::where('company_id', '=', $this->id)
            ->with('orders')
            ->nearby($location, '200', $this->limit)
            ->get();
    
            $this->setRelation('locations', $locations);

            return $this->locations->count();
        } else {
            return false;
        }
        
        $data['distance'] = 200;

        return $data;
    }
    /**
     * [parentAccounts description]
     * 
     * @return [type] [description]
     */
    public function parentAccounts()
    {
        return $this->ancestors();
    }
    /**
     * [scopeSummaryStats description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSummaryStats($query,$period, $branches=null)
    {

        if ($branches) {
            return $this->_summaryBranchStats($query, $period, $branches);
        } else {
            $this->period = $period;
            $this->branches = $branches;
            
            return $query->withCount(       
                [
                
                'locations as supplied_leads'=>function ($query) {
                    $query->whereHas(
                        'assignedToBranch', function ($q) {
                            $q->where('address_branch.created_at', '<=', $this->period['to']);
                        }
                    );
                },
                'locations as offered_leads'=>function ($query) {
                    $query->whereHas(
                        'assignedToBranch', function ($q) {
                            $q->where('status_id', 1)
                                ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                        }
                    );
                },
                'locations as worked_leads'=>function ($query) {
                    $query->whereHas(
                        'assignedToBranch', function ($q) {
                            $q->where('status_id', 2)
                                ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                        }
                    );
                },
                'locations as touched_leads'=>function ($query) {
                    $query->whereHas(
                        'assignedToBranch', function ($q) {
                            $q->where('status_id', '>', 1)
                                ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                        }
                    );
                },
                'locations as rejected_leads'=>function ($query) {
                    $query->whereHas(
                        'assignedToBranch', function ($q) {
                            $q->where('status_id', 4)
                                ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                        }
                    );
                },
                
                'opportunities as new_opportunities'=>function ($query) {
                    
                    $query->whereBetween(
                        'opportunities.created_at', [$this->period['from'],$this->period['to']]
                    );
                        
                },
                'opportunities as won_opportunities'=>function ($query) {
                    $query->whereClosed(1)
                        ->whereBetween(
                            'actual_close', [$this->period['from'],$this->period['to']]
                        );
                },
                
                'opportunities as opportunities_open'=>function ($query) {
                    $query->whereClosed(0)        
                        ->OrWhere(
                            function ($q) {
                                $q->where('actual_close', '>', $this->period['to'])
                                    ->orwhereNull('actual_close');
                            }
                        )
                        ->whereBetween(
                            'opportunities.created_at', [$this->period['from'], $this->period['to']]
                        );
                },
                'opportunities as won_value'=>function ($query) {
                    
                    $query->select(\DB::raw("SUM(value) as wonvalue"))
                        ->whereClosed(1)
                        ->whereBetween(
                            'actual_close', [$this->period['from'],$this->period['to']]
                        );
                        
                   
                },
                'opportunities as open_value'=>function ($query) {
                    
                    $query->select(\DB::raw("SUM(value) as wonvalue"))
                        ->whereClosed(0)        
                        
                        ->whereBetween(
                            'opportunities.created_at', [$this->period['from'],$this->period['to']]
                        );
                        
                    
                }
                
                ]
            );
        }
        
    }
    /**
     * [_summaryBranchStats description]
     * 
     * @param [type] $query    [description]
     * @param [type] $period   [description]
     * @param [type] $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _summaryBranchStats($query, $period, $branches)
    {
        $this->period = $period;
        $this->branches = $branches;
        
        return $query->withCount(       
            [
            'locations as supplied_leads'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {

                        $q->whereIn('branch_id', $this->branches)
                            ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    }
                );
            },
            'locations as offered_leads'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {

                        $q->where('status_id', 1)
                            ->whereIn('branch_id', $this->branches)
                            ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    }
                );
            },
            'locations as worked_leads'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {
                        $q->where('status_id', 2)
                            ->whereIn('branch_id', $this->branches)
                            ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    }
                );
            },
            'locations as touched_leads'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {
                        $q->where('status_id', '>', 1)
                            ->whereIn('branch_id', $this->branches)
                            ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    }
                )->whereHas(
                    'activities', function ($q) {
                        $q->where('completed', 1)
                            ->whereIn('activities.branch_id', $this->branches)
                            ->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                    }
                );
            },
            'locations as rejected_leads'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {
                        $q->where('status_id', 4)
                            ->whereIn('branch_id', $this->branches)
                            ->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    }
                );
            },
            
            'opportunities as new_opportunities'=>function ($query) {
                
                $query->whereBetween(
                    'opportunities.created_at', [$this->period['from'],$this->period['to']]
                )
                    ->whereIn('branch_id', $this->branches);
                    
            },
            'opportunities as won_opportunities'=>function ($query) {
                $query->whereClosed(1)
                    ->whereBetween(
                        'actual_close', [$this->period['from'],$this->period['to']]
                    )
                    ->whereIn('branch_id', $this->branches);
            },
            
            'opportunities as opportunities_open'=>function ($query) {
                $query->where(
                    function ($q) {
                        $q->whereClosed(0)
                            ->orWhere(
                                function ($q) {
                                    $q->where('actual_close', '>', $this->period['to'])
                                        ->orwhereNull('actual_close');
                                }
                            );
                    }
                )        
                ->whereIn('branch_id', $this->branches)
                ->where('opportunities.created_at', '<=', $this->period['to']);
            },
            'opportunities as won_value'=>function ($query) {
                
                $query->select(\DB::raw("SUM(value) as wonvalue"))
                    ->whereClosed(1)
                    ->whereBetween(
                        'actual_close', [$this->period['from'],$this->period['to']]
                    )
                    ->whereIn('branch_id', $this->branches);
                    
               
            },
            'opportunities as open_value'=>function ($query) {
                
                $query->select(\DB::raw("SUM(value) as wonvalue"))
                    ->where(
                        function ($q) {
                            $q->whereClosed(0)
                                ->orWhere(
                                    function ($q) {
                                        $q->where('actual_close', '>', $this->period['to'])
                                            ->orwhereNull('actual_close');
                                    }
                                );
                        }
                    )        
                    ->whereIn('branch_id', $this->branches)
                    ->where('opportunities.created_at', '<=', $this->period['to']);
                    
                
            }
            
            ]
        );
    }

    public function scopeCompanyDetail($query, $period, $branches)
    {
        $this->branches = $branches;
        $this->period = $period;

        return $query->withCount(       
            [
            'locations',
            'locations as assigned'=>function ($query) {
                $query->has('assignedToBranch');
            },
            'locations as offered'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {
                        $q->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                    }
                );
            },
            'locations as worked'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {
                        $q->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']])
                            ->where('status_id', 2);
                    }
                );
            },

            
            ]
        );
    }
    /**
     * [scopeCompanyStats description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeCompanyStats($query)
    {
        return $query->withCount('locations')
            ->withCount(
                ['locations as leads' => function ($q) {
                    $q->whereHas('assignedToBranch');
                },
                'locations as opportunities' => function ($q) {
                    $q->whereHas('opportunities');
                },
                'locations as worked' => function ($q) {
                    $q->whereHas('activities');
                }
                ]
            );
    }

    public function scopeUnassigned($query)
    {
        return $query->with(
            [
                'locations as unassigned'=>function ($q) {
                    $q->doesntHave('assignedToBranch');
                }
            ]
        );  
    }

    public function scopeAssigned($query)
    {
        return $query->with(
            [
                'locations as assigned'=>function ($q) {
                    $q->has('assignedToBranch');
                }
            ]
        );  
    }

}
