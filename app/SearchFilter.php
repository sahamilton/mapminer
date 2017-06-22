<?php

namespace App;
/**
* SearchFilter
*/
class SearchFilter extends NodeModel {

  /**
   * Table name.
   *
   * @var string
   */
  protected $table = 'searchfilters';



  public $tables = ['companies','locations'];
  //////////////////////////////////////////////////////////////////////////////

  //
  // Below come the default values for Baum's own Nested Set implementation
  // column names.
  //
  // You may uncomment and modify the following fields at your own will, provided
  // they match *exactly* those provided in the migration.
  //
  // If you don't plan on modifying any of these you can safely remove them.
  //

  // /**
  //  * Column name which stores reference to parent's node.
  //  *
  //  * @var string
  //  */
  // protected $parentColumn = 'parent_id';

  // /**
  //  * Column name for the left index.
  //  *
  //  * @var string
  //  */
  // protected $leftColumn = 'lft';

  // /**
  //  * Column name for the right index.
  //  *
  //  * @var string
  //  */
  // protected $rightColumn = 'rgt';

  // /**
  //  * Column name for the depth field.
  //  *
  //  * @var string
  //  */
  // protected $depthColumn = 'depth';

  // /**
  //  * Column to perform the default sorting
  //  *
  //  * @var string
  //  */
//protected $orderColumn = 'seq';

  // /**
  // * With Baum, all NestedSet-related fields are guarded from mass-assignment
  // * by default.
  // *
  // * @var array
  // */
  // protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');

  //
  // This is to support "scoping" which may allow to have multiple nested
  // set trees in the same database table.
  //
  // You should provide here the column names which should restrict Nested
  // Set queries. f.ex: company_id, etc.
  //

  // /**
  //  * Columns which restrict what we consider our Nested Set list
  //  *
  //  * @var array
  //  */
  // protected $scoped = array();

  //////////////////////////////////////////////////////////////////////////////

  //
  // Baum makes available two model events to application developers:
  //
  // 1. `moving`: fired *before* the a node movement operation is performed.
  //
  // 2. `moved`: fired *after* a node movement operation has been performed.
  //
  // In the same way as Eloquent's model events, returning false from the
  // `moving` event handler will halt the operation.
  //
  // Below is a sample `boot` method just for convenience, as an example of how
  // one should hook into those events. This is the *recommended* way to hook
  // into model events, as stated in the documentation. Please refer to the
  // Laravel documentation for details.
  //
  // If you don't plan on using model events in your program you can safely
  // remove all the commented code below.
  //

  // /**
  //  * The "booting" method of the model.
  //  *
  //  * @return void
  //  */
  // protected static function boot() {
  //   // Do not forget this!
  //   parent::boot();

  //   static::moving(function($node) {
  //     // YOUR CODE HERE
  //   });

  //   static::moved(function($node) {
  //     // YOUR CODE HERE
  //   });
  // }
	public  $rules = [
		 'filter' => 'required',
		 'type' =>'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['filter','type','searchtable','searchcolumn','canbenull','inactive','color'];
	
	public function setSearch($search = NULL)
	{
		$searchFilter = array();		
		$searchFilters = array();
		// Initialize the search session
		if(! isset($search)) {
			$search = \Session::get('Search');
			
			if(count($search) < 2)
			{
				// If session isn't set then get all filters
				$keys = $this->whereNotNull('type')->orderBy('searchtable','searchcolumn','lft')->pluck('id');
				
			}else{
				//get filters from session
				$keys = array_keys($search);
				
			}
		}else{
			$keys = array_keys($search);
			
		}

		$filters = $this->whereIn('id',$keys)->whereNotNull('type')->orderBy('lft')->get();

		foreach ($filters as $filter)
		{				
				
				// we need to set the vertical parent to checked if depth > 2
				if ($filter->isLeaf() && $filter->depth > 2 && $filter->inactive == 0){
					// set the parent to the be checked
					$parent = $filter->parent()->get();
					if(! isset($searchFilter[$parent[0]['searchtable']][$parent[0]['searchcolumn']][$parent[0]['id']])){
						
						$searchFilter[$parent[0]['searchtable']][$parent[0]['searchcolumn']][$parent[0]['id']]=$parent[0]['id'];
					}
					$searchFilter[$filter->searchtable][$filter->searchcolumn][$filter->id] = $filter->id;
				}
				// We dont want to set it twice!
				if(! isset ($searchFilter[$filter->searchtable][$filter->searchcolumn][$filter->id])){
					
					$searchFilter[$filter->searchtable][$filter->searchcolumn][$filter->id] = $filter->id;
					
				}

			}
		// Clean out any junk	
		/*foreach ($searchFilter as $key=>$value)
		{
			if (in_array($key,$this->tables)) {
				
				$searchFilters[$key] = $value;
			}
		}*/
		
		

		\Session::forget('Search');

		\Session::put('Search', array($searchFilter));
		
	}
	


  public function vertical(){

    return $this->where('searchColumn','=','vertical')
        ->where('canbenull','=',0)
        ->where('type','!=','group')
        ->orderBy('filter')
        ->pluck('filter','id');

  }

  public function industrysegments(){

    $filters = $this->first();
    return $filters->getDescendants()
    ->where('searchtable','=','companies')->where('inactive','=',0);

  }

  public function companies(){
    return $this->hasMany(Company::class,'vertical','id');
  }

  public function leads(){
    return $this->belongsToMany(Lead::class,'lead_searchfilter','searchfilter_id')
      ->where('datefrom','<=',date('Y-m-d'))
      ->where('dateto','>=',date('Y-m-d'));
  }
  public function people(){
    return $this->belongsToMany(Person::class, 'person_search_filter','search_filter_id')
    ->withTimestamps();
  }

  public function campaigns(){
    return $this->belongsToMany(Salesactivity::class,'activity_process_vertical','vertical_id','activity_id')
    ->groupBy(['vertical_id','activity_id'])
    ->where('datefrom','<=',date('Y-m-d'))
    ->where('dateto','>=',date('Y-m-d'))
    ->withPivot('salesprocess_id');
  }

  public function segment(){
      return $this->hasMany(Location::class,'segment')->count();


  }


  public function locations(){
    $count = 0; 
    $companies = Company::where('vertical','=',$this->id)->get();
    

        foreach ($companies as $company){
          $count = $count + $company->locations()->count();
        }

    return $count;
  }
	
}
