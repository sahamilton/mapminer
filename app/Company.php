<?php
namespace App;
use Nicolaslopezj\Searchable\SearchableTrait;

class Company extends NodeModel {
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
	protected $fillable = array('companyname', 'vertical','person_id','c','customer_id','parent_id');
	
	public function type() 
	{
		return $this->belongsTo(AccountType::class,'accounttypes_id');
	}
	
	public function locations() 
	{
								
			return $this->hasMany(Address::class);
	
	}

	public function stateLocations($state){
			return $this->hasMany(Address::class)->where('state','=',$state);
	}

	public function countlocations()

	{

		return $this->hasMany(Address::class)->selectRaw('company_id,count(*) as count')->groupBy('company_id');

	}
	
	public function locationcount()

	{

		return $this->hasMany(Address::class)->selectRaw('company_id,count(*) as count')->groupBy('company_id')->first();

	}

	
	public function managedBy()
	
	{
		return $this->belongsTo(Person::class,'person_id');
	}
	
	public function serviceline()
	
	{
		return $this->belongsToMany(Serviceline::class)->withTimestamps();
	}

	public function industryVertical()
	
	{
		return $this->hasOne(SearchFilter::class,'id','vertical');
	}
	
	public function salesNotes()
	
	{
		return $this->belongsToMany(Howtofield::class);
	}
	

	public function getFilteredLocations($filtered, $keys,$query,$paginate= NULL)
	{
		
			$columns = ['segment','businesstype'];
			//note we turned off business type.  When ready add it back into the array
			
			
			$isNullable = $this->isNullable($keys,$columns);
			
			if($filtered) {
				
			/*	foreach ($columns as $column){
					if(isset($isNullable[$column]) && $isNullable[$column]){
						$query->where(function($q) use ($filtered,$keys, $isNullable,$column) {
							$q->whereIn($column,$keys)
							->orWhereNull($column)->get();
						});$this->locations->where('company_id','=',$company_id)->whereIn('segment',$segment_keys)->whereIn('businesstype',$businesstype_keys)->get()
					}else{
						$query->whereIn($column,$keys)->get();
					}
					
				}*/

				
					
								
			}

			if($paginate)
			{
				//$locations = $query->paginate($paginate);
				$locations = $query->get();
			}else{
				$locations = $query->get();
			}			

			return $locations;
	}
	/**
	 * Check that user can access company based on user
	 * serviceline settings
	 * @param  integer $company_id Company ID
	 * @return Object $company with servicelines 
	 *                         in user servicelines settings
	 */
	public function checkCompanyServiceLine($company_id,$userServiceLines)
	{
		dd($this->userServiceLines);
		return $this->whereHas('serviceline', function($q) use ($userServiceLines) {
						    $q->whereIn('serviceline_id', $userServiceLines);

						})->with('industryVertical')
						->find($company_id);
	}
	public function getAllCompanies($filtered=null)
	{
		dd($this->userServiceLines);
		$keys=array();

		$companies = $this->with('managedBy','managedBy.userdetails','industryVertical','serviceline','countlocations')
			->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

			});

		if($filtered) {
			$keys = $this->getSearchKeys(['companies'],['vertical']);
			$isNullable = $this->isNullable($keys,NULL);
			$companies = $companies->whereIn('vertical',$keys);

			if($isNullable == 'Yes')
			{

					$companies = $companies->orWhere(function($query) use($keys)
					{
						$query->whereNull('vertical');
					});

			}

		}

		return $companies->orderBy('companyname');

	}

	public function limitLocations($data){
		if($data['company']->locations->count() > $this->limit){

			$locations = Address::where('company_id','=',$data['company']->id)
			->with('orders')->nearby($data['mylocation'],'200',$this->limit)->get();
	
			$data['company']->setRelation('locations',$locations);

			$data['limited']=$data['company']->locations->count();
			
			
		}else{
			$data['limited']= false;
		}
		
		$data['distance'] = 200;

		return $data;
	}
	
	public function parentAccounts(){
		return $this->ancestors();
	}
}