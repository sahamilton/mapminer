<?php
namespace App;

use Nicolaslopezj\Searchable\SearchableTrait;

class Company extends NodeModel
{
    use Filters,SearchableTrait;
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
    protected $fillable = ['companyname', 'vertical','person_id','c','customer_id','parent_id','accounttypes_id'];
    /**
     * [type description]
     * 
     * @return [type] [description]
     */
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
    public function limitLocations(Array $data)
    {
        if ($this->locations->count() > $this->limit) {
            $locations = Address::where('company_id', '=', $this->id)
            ->with('orders')
            ->nearby($data['mylocation'], '200', $this->limit)
            ->get();
    
            $this->setRelation('locations', $locations);

            $data['limited']=$this->locations->count();
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
    public function scopeSummaryStats($query,$period)
    {
        $this->period = $period;
        return $query->withCount(       
            ['locations as leads'=>function ($query) {
                $query->whereHas(
                    'assignedToBranch', function ($q) {
                        $q->where('address_branch.created_at', '<=', $this->period['to'])
                            ->where(
                                function ($q) {
                                    $q->whereDoesntHave('opportunities');
                                }
                            );
                    }
                );
            },
            'locations as activities'=>function ($query) {
                $query->whereHas(
                    'activities', function ($query) {
                        $query->whereBetween(
                            'activity_date', [$this->period['from'],$this->period['to']]
                        )->where('completed', 1);
                    }
                );
            },
            'locations as salesappointments'=>function ($query) {
                $query->whereHas(
                    'activities', function ($query) {
                        $query->whereBetween(
                            'activity_date', [$this->period['from'],$this->period['to']]
                        )->where('completed', 1)
                            ->where('activitytype_id', 4);
                    }
                );
            },
            'locations as newopportunities'=>function ($query) {
                $query->whereHas(
                    'opportunities', function ($query) {
                        $query->whereBetween(
                            'opportunities.created_at', [$this->period['from'],$this->period['to']]
                        );
                    }
                );
            },
            'locations as wonopportunities'=>function ($query) {
                $query->whereHas(
                    'opportunities', function ($query) {
                        $query->whereClosed(1)
                            ->whereBetween(
                                'actual_close', [$this->period['from'],$this->period['to']]
                            );
                    }
                );
            },
            'locations as opportunitieslost'=>function ($query) {
                $query->whereHas(
                    'opportunities', function ($query) {
                        $query->whereClosed(2)
                            ->whereBetween(
                                'actual_close', [$this->period['from'],$this->period['to']]
                            );
                    }
                );
            },
            'locations as opportunitiesopen'=>function ($query) {
                $query->whereHas(
                    'opportunities', function ($query) {
                        $query->whereClosed(0)        
                            ->OrWhere(
                                function ($q) {
                                    $q->where('actual_close', '>', $this->period['to'])
                                        ->orwhereNull('actual_close');
                                }
                            )
                        ->where('opportunities.created_at', '<', $this->period['to']);
                    }
                );
            },
            'locations as wonvalue'=>function ($query) {
                $query->whereHas(
                    'opportunities', function ($q) {
                        $q->select(\DB::raw("SUM(value) as wonvalue"))
                            ->where('closed', 1)
                            ->whereBetween(
                                'actual_close', [$this->period['from'],$this->period['to']]
                            );
                    }
                );
            },
            'locations as openvalue'=>function ($query) {
                $query->whereHas(
                    'opportunities', function ($q) {
                        $q->select(\DB::raw("SUM(value) as wonvalue"))
                            ->whereClosed(0)        
                            ->OrWhere(
                                function ($q1) {
                                    $q1->where('actual_close', '>', $this->period['to'])
                                        ->orwhereNull('actual_close');
                                }
                            )
                        ->where('opportunities.created_at', '<', $this->period['to']);
                    }
                );
            }

            ]
        );



    }
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

}
