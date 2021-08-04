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
    public $branches;
    public $activityFields = [
            '4'=>'Sales Appointment',
            '5'=>'Stop By',
            '7'=>'Proposal',
            '10'=>'Site Visit',
            '13'=>'Log a call',
            '14'=>'In Person'

    ];
    public $leadFields = [
            'open_leads',
            'active_leads',
            "supplied_leads",
            "offered_leads",
            "worked_leads",
            "rejected_leads",
            "touched_leads",
            
        ];
    public $opportunityFields = [
                "active_opportunities",
                "lost_opportunities",
                "new_opportunities",
                "open_opportunities",
                "top25_opportunities",
                "won_opportunities",
                "active_value",
                "lost_value",
                "new_value",
                "open_value",
                "top25_value",
                "won_value"
                ];
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
            'company_id', // Foreign key on address table...
            'address_id', // Foreign key on opportunity table...
            'id', // Local key on companies table...
            'id' // Local key on address table...
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
     * @param [type] $location [description]
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
     * [scopeLeadSummary description]
     * 
     * @param [type] $query  [description]
     * @param [type] $period [description]
     * 
     * @return [type]         [description]
     */
    public function scopeLeadSummary($query,$period, $branches, $fields = null)
    {

        if (! $fields) {
            $fields = $this->leadFields;
        }
        $this->fields = $fields;
        $this->period = $period;
        $this->branches = $branches;
        /*
            'open_leads',
            'active_leads',
            "supplied_leads",
            "offered_leads",
            "worked_leads",
            "rejected_leads",
            "touched_leads",

         */
        return $query
            ->when(
                in_array('open_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'locations as open_leads'=>function ($query) {
                                $query->whereHas(
                                    'assignedToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->branches);
                                    }
                                )->openLeads($this->period);

                            }
                        ]
                    );
                }
            )
            ->when(
                // note that this and touched leads should return the same
                // consider merging.
                in_array('active_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'locations as active_leads'=>function ($query) {
                                $query->whereHas(
                                    'assignedToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->branches);
                                    }
                                )->activeLeads($this->period);

                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('supplied_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'locations as supplied_leads'=>function ($query) {
                                $query->whereHas(
                                    'assignedToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->branches);
                                    }
                                )->suppliedLeads($this->period);
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('offered_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'locations as offered_leads'=>function ($query) {
                                $query->whereHas(
                                    'assignedToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->branches);
                                    }
                                )->offeredLeads($this->period);
                            
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('worked_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'locations as worked_leads'=>function ($query) {
                                $query->whereHas(
                                    'assignedToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->branches);
                                    }
                                )->workedLeads($this->period);
                            }
                        ]
                    );
                }
            )->when(
                in_array('touched_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'locations as touched_leads'=>function ($query) {
                                $query->whereHas(
                                    'assignedToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->branches);
                                    }
                                )->touchedLeads($this->period);
                            }
                        ]
                    );
                }
            )->when(
                in_array('rejected_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'locations as rejected_leads'=>function ($query) {
                                $query->whereHas(
                                    'assignedToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->branches);
                                    }
                                )->rejectedLeads($this->period);
                                
                            }
                        ]
                    );
                }
            )
            ->when(
                in_array('new_leads', $this->fields), function ($q) {
                    $q->withCount(       
                        [
                            'locations as new_leads'=>function ($query) {

                                $query->whereHas(
                                    'assignedToBranch', function ($q) {
                                        $q->whereIn('branches.id', $this->branches);
                                    }
                                )->newLeads($this->period);
                                    
                               
                            }
                        ]
                    );
                }
            )
        ->when(
            in_array('unassigned_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as unassigned_leads'=>function ($query) {

                            $query->unassigned($this->period);
                                
                           
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('top_25leads', $this->fields), function ($q) {

                $q->withCount(       
                    [
                        'locations as top_25leads'=>function ($query) {

                            $query->top25($period);                                
                           
                        }
                    ]
                );
            }
        )
        
        ->when(
            in_array('open_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as open_leads'=>function ($query) {
                            $query->whereHas(
                                'assignedToBranch', function ($q) {
                                    $q->whereIn('branches.id', $this->branches);
                                }
                            )->openLeads($this->period);

                        }
                    ]
                );
            }
        );

        
    }

    public function scopeCompanyCampaignSummaryStats($query, Campaign $campaign, $fields = null)
    {
        
        if (! $fields) {
            $fields = $this->summaryFields;
        }
        $this->campaign = $campaign;
        $this->fields = $fields;
        $this->period = ['from'=>$campaign->datefrom, 'to'=>$campaign->dateto];
        
        return $query->when(
            in_array('campaign_leads', $this->fields), function ($q) {
                $q->withCount(
                    [
                        'locations as campaign_leads'=>function ($q) {
                            $q->whereHas(
                                'campaigns', function ($q) {
                                    $q->where('campaign_id', $this->campaign->id);
                                }
                            );
                        }
                    ]
                );
            }
        )->when(
            in_array('touched_leads', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'locations as touched_leads'=>function ($query) {
                            $query->whereHas(
                                'campaigns', function ($q) {
                                    $q->where('campaign_id', $this->campaign->id);
                                }
                            )->touchedLeads($this->period);
                        }
                    ]
                );
            }
        )->when(
            in_array('new_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as new_opportunities'=>function ($q1) {
                
                            $q1->newOpportunities($this->period);
                                
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('open_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as open_opportunities'=>function ($q1) {
                            $q1->open($this->period);
                        }
                    ]
                );
            }
        )->when(
            in_array('won_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as won_opportunities'=>function ($q1) {
                            $q1->won($this->period);
                        }
                    ]
                );
            }
        )->when(
            in_array('won_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as won_value'=>function ($query) {
                
                            $query->wonValue($this->period);
                                
                           
                        }
                    ]
                );
            }
        );  


    }
    public function scopeOpportunitySummary($query, $period, $branches, $fields = null)
    {
    
        if (! $fields) {
            $fields = $this->opportunityFields;
        }
        $this->period = $period;
        $this->branches = $branches;
        $this->fields = $fields;
        return $query->when(
            in_array('active_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as active_opportunities'=>function ($q1) {
                            $q1->currentlyActive($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);
                        }
                    ]
                );
            }
        )->when(
            in_array('lost_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as lost_opportunities'=>function ($q1) {
                            $q1->lost($this->period)
                            ->whereIn('opportunities.branch_id', $this->branches);;
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('new_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as new_opportunities'=>function ($q1) {
                
                            $q1->newOpportunities($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                                
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('open_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as open_opportunities'=>function ($q1) {
                            $q1->open($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                        }
                    ]
                );
            }
        )  
        ->when(
            in_array('top_25opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as top_25opportunities'=>function ($q1) {
                            $q1->top25($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('won_opportunities', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as won_opportunities'=>function ($q1) {
                            $q1->won($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('active_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as active_value'=>function ($query) {
                            $query->activeValue($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('lost_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as lost_value'=>function ($query) {
                
                            $query->lostValue($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                                
                           
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('new_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as new_value'=>function ($query) {
                
                            $query->newValue($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                                
                           
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('open_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as open_value' => function ($q1) {
                            $q1->openValue($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                                
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('top_25value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as top_25value'=>function ($query) {
                            $query->top25Value($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);
                        }
                    ]
                );
            }
        )
        ->when(
            in_array('won_value', $this->fields), function ($q) {
                $q->withCount(       
                    [
                        'opportunities as won_value'=>function ($query) {
                
                            $query->wonValue($this->period)
                                ->whereIn('opportunities.branch_id', $this->branches);;
                                
                           
                        }
                    ]
                );
            }
        );

    }

    public function scopeCompanyDetail($query, $period)
    {
       
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
            'opportunities as open_opportunities'=>function ($q1) {
                $q1->open($this->period);
            },
            
            ]
        )->withSum(
            [
                'opportunities as open_value'=>function ($query) {
                    $query->open($this->period);
                },
            ], 'value'
        );
    }
    /**
     * [scopeCompanyStats description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeCompanyStats($query, $period = null)
    {
        $this->period = $period;
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
    public function activities()
    {
      
        return $this->hasManyThrough(
            Activity::class,
            Address::class,
            'company_id', // Foreign key on users table...
            'address_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
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
    public function scopeActivityDetail($query, $period)
    {
        return $query->with(
            [
                'activities'=>function ($q) use ($period) {
                    $q->whereBetween('activity_date', [$period['from'], $period['to']])
                        ->where('completed', 1);
                }
            ]
        );
    }
    public function scopeActivitySummaryByType($query, array $period, array $fields)
    {
        return $query->with(
            ['activities'=>function ($q) {
                $q
                    ->whereBetween('activity_date', [$period['from'], $period['to']])
                    ->where('completed', 1)
                    ->selectRaw('activitytype_id, count(activities.id)');
            }
            ]
        )
        
        ->groupBy('activitytype_id');


    }
    public function scopeSearch($query, $search)
    {
        $query->where('companyname', 'like', "%{$search}%");
    }
    public function scopeActivitiesTypeCount($query, $period)
    {
      return $query->with(['activities'=>function ($q) use ($period) {
            $q->periodActivities($period)
                ->completed()
                ->selectRaw("addresses.company_id, activity_type.activity,count(activities.id) as activities")
                ->join('activity_type', 'activities.activitytype_id', '=', 'activity_type.id')
               
                ->groupBy(['addresses.company_id', 'activity_type.activity']);

            }
        ]
        );
       
    }
    public function lastUpdated()
    {
        return $this->belongsTo(Address::class);
    }
    /**
     * [scopeWithLastUpdatedId description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    public function scopeWithLastUpdatedId($query)
    {
         return $query
             ->select('companies.*')
             ->selectSub('select id as last_updated_id from addresses where company_id = companies.id order by addresses.created_at desc limit 1', 'last_updated_id');
       
    }   

    public function scopePipeline($query, $period=null)
    {
        if (! $period) {
            $period = ['from'=>now(), 'to'=>now()->addMonths(2)];
        }
        $this->period = $period;
        return $query->with(
            ['opportunities' => function ($q) {
                $q->whereBetween('expected_close', [$this->period['from'], $this->period['to']])
                    ->where('closed', 0)
                    ->selectRaw('FROM_DAYS(TO_DAYS(expected_close) -MOD(TO_DAYS(expected_close) -2, 7)) as yearweek, sum(value) as funnel')
                    ->groupBy('expected_close');
            }
            ]
        );
    }

}
