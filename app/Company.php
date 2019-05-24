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
    protected $fillable = ['companyname', 'vertical','person_id','c','customer_id','parent_id'];
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
        return $this->belongsToMany(Howtofield::class);
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
    public function limitLocations($data)
    {
        if ($data['company']->locations->count() > $this->limit) {
            $locations = Address::where('company_id', '=', $data['company']->id)
            ->with('orders')
            ->nearby($data['mylocation'], '200', $this->limit)
            ->get();
    
            $data['company']->setRelation('locations', $locations);

            $data['limited']=$data['company']->locations->count();
        } else {
            $data['limited']= false;
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
}
