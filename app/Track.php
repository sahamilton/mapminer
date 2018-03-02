<?php
namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
class Track extends Model {

	// Add your validation rules here
	public static $rules;
	protected $table = 'track';
	// Don't forget to fill this array
	public $fillable = ['user_id','lastactivity'];
	public $dates = ['lastactivity'];
	public $errors;
	

	public function scopeLastLogin($query,$interval=null){

		$sub = $query->selectRaw('`user_id`,max(`lastactivity`) as `lastlogin`')
		->groupBy('user_id');
	// this should be a join
		$lastlogin = $query->join("({$sub->toSql()}) as max")
    			->mergeBindings($sub->getQuery());
    	if($interval){
    		return $lastlogin->whereBetween('max.lastlogin',$interval);
    	}
    		return $lastlogin->whereNull('max.laslogin');	
		
	}

	public function user(){
		return $this->belongsTo(User::class);
	}

	
}