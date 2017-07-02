<?php
namespace App;

use Carbon\Carbon;

class News extends Model {

	
	// Don't forget to fill this array
	protected $fillable = ['title','news','datefrom','dateto','slug','user_id'];
	public $dates =  ['created_at','updated_at','datefrom','dateto'];

	public function author()
	{
		return $this->belongsTo(User::class,'user_id','id');
	}
	
	public function comments()
	{
		return $this->hasMany(Comments::class);
	}

	public function serviceline()
	{
		return $this->belongsToMany(Serviceline::class);
	}

	public function relatedRoles(){
		return $this->belongsToMany(Role::class);
	}
	public function relatedIndustries(){
		return $this->belongsToMany(SearchFilter::class,'news_searchfilter','news_id','searchfilter_id');
	}

	public function currentNews(){
		$nonews = auth()->user()->nonews;
		$now = Carbon::now('America/Vancouver')->toDateTimeString();
		if(! isset($nonews)){
			$nonews = Carbon::now('America/Vancouver')->subYear()->toDateTimeString();
				 
		}
;
		return $this->where('datefrom','>=',$nonews)
			->where('dateto','>=',$now)
			->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->getUserServiceLines());

			})
			->where(function ($query){
				$query->whereHas('relatedIndustries',function($q){
					$q->whereIn('searchfilter_id',auth()->user()->person()->first()->industryfocus()->pluck('searchfilters.id')->toArray());
				})
				->orWhere(function ($q){
					$q->doesntHave('relatedIndustries');
				});
			})
			->where(function ($query){
				$query->whereHas('relatedRoles',function($q){
					$q->whereIn('role_id',auth()->user()->roles->pluck('id')->toArray());
				})
				->orWhere(function ($q){
					$q->doesntHave('relatedRoles');
				});
			})
			->get();
			


	}
}