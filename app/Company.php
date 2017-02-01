<?php
namespace App;

class Company extends Model {

	// Add your validation rules here
	public static $rules = [
		 'companyname' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = array('companyname', 'vertical','person_id','user_id');
	
	public function type() 
	{
		return $this->belongsTo(AccountType::class,'accounttypes_id');
	}
	
	public function locations() 
	{
		
								
			return $this->hasMany(Location::class);
	
	}

	public function countlocations()

	{

		return $this->hasMany(Location::class)->selectRaw('company_id,count(*) as count')->groupBy('company_id');

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
		
		$checkCompany = $this->whereHas('serviceline', function($q) use ($userServiceLines) {
						    $q->whereIn('serviceline_id', $userServiceLines);

						})

						->find($company_id);
		return $checkCompany;
	}
	
}