<?php
namespace App;
use\App\Presenters\LocationPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
class Person extends NodeModel implements HasPresenter {
	use Geocode,Filters;

	// Add your validation rules here
	public static $rules = [
	'email'=>'required',
	'mgrtype' => 'required'
	];
	protected $table ='persons';

	protected $parentColumn = 'reports_to';

	protected $dates =['created_at','updated_at','active_from'];
	// Don't forget to fill this array
	public $fillable = ['firstname','lastname','phone','address','lat','lng','reports_to','city','state','geostatus','user_id','active_from'];

	
	public function reportsTo()
    {
        return $this->belongsTo(Person::class, 'reports_to','id');
    }

    public function directReports()
    {
        return $this->hasMany(Person::class, 'reports_to');
    }
	public function salesRole()
	{
		return $this->belongsTo(SalesOrg::class,'id','position');
	}
	
	public function branchesServiced()
	{
		return $this->belongsToMany(Branch::class)->withPivot('role_id');
	}
	

	public function manages() {
		
		return $this->belongsToMany(Branch::class)->withPivot('role_id');

	}
	public function comments () {
		
		return $this->hasMany(Comment::class);

	}
	public function managesAccount () {
		
		return $this->hasMany(Company::class);

	}

	public function projects(){
      return $this->belongsToMany(Project::class)->withPivot('status');
    }
    
	public function userdetails()
	 {
		  return $this->belongsTo(User::class,'user_id','id');
	 }

	public function authored () {
		
		return $this->hasMany(News::class);

	}
	
	public function fullName()
	{
		return $this->attributes['lastname'] . ',' . $this->attributes['firstname'];
	}
	
	public function postName()
	{
		return $this->attributes['firstname'] . ' ' . $this->attributes['lastname'];
	}
	
	public function industryfocus()
	{
		return $this->belongsToMany(SearchFilter::class)->withTimestamps(); 
	}
	public function getPresenterClass()
    {
        return LocationPresenter::class;
    }
	public function personroles($roles){


		return $this->wherehas('userdetails.roles', function($q) use($roles){
					$q->whereIn('role_id',$roles);
				})
		->with('userdetails','userdetails.roles')
		->orderBy('lastname')
		->get();
	}

	public function getPersonsWithRole($roles){
		return $this->select(\DB::raw("CONCAT(lastname,' ' ,firstname) AS fullname, id"))
			->whereHas('userdetails.roles', 
				function($q) use($roles){
					$q->whereIn('role_id',$roles);
				})
			->orderBy('lastname')->get();
			
	}
	public function salesleads(){
		return $this->belongsToMany(Lead::class, 'lead_person_status','person_id','related_id')
		->withTimestamps()
		->wherePivot('type','=','prospect')
		->withPivot('status_id','rating','type');
	}
	
	public function leadratings(){
      	return  $this->belongsToMany(Lead::class, 'lead_person_status','person_id','related_id')
		->withTimestamps()
		->wherePivot('type','=','prospect')
		->withPivot('status_id','rating','type')
		->whereNotNull('rating');
	
    }


	public function findPersonsRole($people)
	{
		
		foreach ($people->userdetails->roles as $role)
		{
			$result[] = $role->name;
		}
		
		return $result;
		
	}
	public function findRole()
	{
		
		foreach ($this->userdetails->roles as $role)
		{
			$result[] = $role->id;
		}
	
		return $result;
		
	}

	public function salesLeadsByStatus($id){
		$leads = $this->with('salesleads')
			->whereHas('salesleads.leadsource',function($q){
				$q->where('datefrom','<=',date('Y-m-d'))
				->where('dateto','>=',date('Y-m-d'));
			})
			->find($id);

		foreach ($leads->salesleads as $lead){
			if(! isset($statuses[$lead->pivot->status_id])){
				$statuses[$lead->pivot->status_id]['status']=$lead->pivot->status_id;
				$statuses[$lead->pivot->status_id]['count']=0;
			}
			$statuses[$lead->pivot->status_id]['count']+=1;
			
		}
		return $statuses;
	}
	
	private function getPersonsServiceLines(){

		foreach($this->person->serviceline as $serviceline){

			$servicelines[]=$serviceline->id;
		}
		$this->personServicelines = implode("','",$servicelines);
		
	}

	public function ownedLeads(){
		return $this->belongsToMany(Lead::class, 'lead_person_status','person_id','related_id')
		->withTimestamps()
		->withPivot('status_id','rating')
		->whereIn('status_id',[2,3]);
	}


	public function myOwnedLeads(){
		return $this->belongsToMany(Lead::class, 'lead_person_status','person_id','related_id')
		->withTimestamps()
		->withPivot('status_id','rating')
		->whereIn('status_id',[2])
		->where('person_id','=',auth()->user()->person->id);
	}

	public function offeredLeads(){

		return $this->belongsToMany(Lead::class, 'lead_person_status','person_id','related_id')
		->withTimestamps()
		->withPivot('status_id','rating')
		->whereIn('status_id',[1]);
        
        
       
    }

    public function campaigns(){
    	return $this->belongsToMany(Salesactivity::class);
    }

    public function campaignparticipants($vertical){
    	return $this->whereHas('industryfocus', function($q) use($vertical){
                        $q->whereIn('search_filter_id',$vertical);
                })
                ->whereHas('userdetails',function ($q){
                    $q->where('confirmed','=',1);
                })
                ->where(function($q){
                    $q->whereNull('active_from')
                    ->orWhere('active_from','<=',date('Y-m-d'));
                });

    }
}