<?php
namespace App;
use\App\Presenters\LocationPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
class Location extends Model implements HasPresenter {

	use Geocode;

	// Add your validation rules here
	public static $rules = [
		'businessname' => 'required',
		'street' => 'required',
		'city' => 'required',
		'state' => 'required',
		'zip' => 'required',
		'company_id' => 'required',
		'segment' => 'required',
		'businesstype' => 'required'

	];


	public $table = 'locations';
	public $branch;


	public $fillable = ['businessname','street','address2','city','state','zip','company_id','phone','contact','lat','lng','segment','businesstype'];

	protected $hidden =  array('created_at','updated_at','id');
/**
 * [relatedNotes description]
 * @return [type] [description]
 */
	public function relatedNotes() {

		return $this->hasMany(Note::class,'related_id')->where('type','=','location')->with('writtenBy');

	}
/**
 * [company description]
 * @return [type] [description]
 */
	public function company() {

		return $this->belongsTo(Company::class)->with('managedBy');

	}
/**
 * [branch description]
 * @return [type] [description]
 */
	public function branch () {

		return $this->belongsTo(Branch::class);

	}

/**
 * [contacts description]
 * @return [type] [description]
 */
	public function contacts(){
		return $this->hasMany(Contacts::class);
	}

/**
 * [instate description]
 * @return [type] [description]
 */
	public function instate () {

		return $this->belongsTo(State::class,'state','statecode');

	}
/**
 * [verticalsegment description]
 * @return [type] [description]
 */
	public function verticalsegment () {
		return $this->hasOne(SearchFilter::class,'id','segment');
	}
/**
 * [clienttype description]
 * @return [type] [description]
 */
	public function clienttype() {

		return $this->hasOne(SearchFilter::class,'id','businesstype');
	}

/**
 * [watchedBy description]
 * @return [type] [description]
 */

	public function watchedBy(){

		return $this->belongsToMany(User::class,'location_user','location_id','user_id')->withPivot('created_at','updated_at');
	}

	public function getPresenterClass()
    {
        return LocationPresenter::class;
    }

/**
 * [nearbyBranches description]
 * @return [type] [description]
 */
	public function nearbyBranches($servicelines){

		return Branch::whereHas('servicelines', function($q) use ($servicelines){
				$q->whereIn('servicelines.id',$servicelines);
			})->nearby($this,'100')->limit(5);
	}


	public function nearbySalesRep($serviceline=null){

		return Person::with('userdetails.roles')
		->whereHas('userdetails.serviceline',function ($q) use ($serviceline){
			$q->whereIn('servicelines.id',$serviceline);
		})
		->whereHas('userdetails.roles',function ($q){

			$q->where('roles.id','=',5);
		})
		->nearby($this,'100')
        ->limit(5);

		//return Branch::nearby($this,'100')->limit(5);
	}



	public function locationAddress(){
		return ($this->street . " " . $this->address2 . " " .$this->city . " " . $this->state);
	}
	/*
		Generate Mapping xml file from location results

		Passed to function:
		@ results
		@return xml

	*/

	public function makeNearbyLocationsXML($result) {
		$content = view('locations.xml', compact('result'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');

	}
	/**
	 * function getStateSummary
	 * @param  int $id Company ID
	 * @return Collections     location count by state for company
	 */
	public function getStateSummary($id){
		return $this->where('company_id','=',$id)
			->select('state', \DB::raw('count(*) as total'))
             ->groupBy('state')
             ->pluck('total','state');
	}
/**
 * [getQuerySearchKeys description]
 * @return [type] [description]
 */
	private function getQuerySearchKeys(){
			$keys = array();
			$searchKeys = array();

			$keys['vertical'] = $this->getSearchKeys(['companies'],['vertical']);

			if(count($keys['vertical']) > 0){

				$searchKeys['vertical']['keys'] = implode("','",$keys['vertical']);
				$searchKeys['vertical']['null']= $this->isNullable($keys['vertical']);
			}


			$keys['segment'] = $this->getSearchKeys(['locations'],['segment','businesstype']);
			if(count($keys['segment']) > 0){

				$searchKeys['segment']['keys'] = implode("','",$keys['segment']);
				$searchKeys['segment']['null'] = $this->isNullable($keys['segment']);
			}


			$keys['businesstype'] = $this->getSearchKeys(['locations'],['businesstype']);

			if(count($keys['businesstype']) > 0){

				$searchKeys['businesstype']['keys'] = implode("','",$keys['businesstype']);
				$searchKeys['businesstype']['null'] = $this->isNullable($keys['businesstype']);

			}

			return $searchKeys;
	}

}
