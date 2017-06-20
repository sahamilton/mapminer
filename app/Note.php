<?php
namespace App;
class Note extends Model {

	// Add your validation rules here
	public static $rules = [
		'note' => 'required'
	];
	public $dates = ['date'];
	// Don't forget to fill this array
	protected $fillable = ['note','location_id','lead_id','user_id','created_at','updated_at'];
protected $table ='notes';

	public function writtenBy() 
		{
			return $this->belongsTo(User::class,'user_id')->with('person');
		}
		
	public function relatesTo() 
		{
			return $this->belongsTo(Location::class,'location_id');
		}
	public function relatesToLead() 
		{
			return $this->belongsTo(Lead::class,'lead_id');
		}
}