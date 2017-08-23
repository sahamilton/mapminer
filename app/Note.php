<?php
namespace App;
class Note extends Model {

	// Add your validation rules here
	public static $rules = [
		'note' => 'required'
	];
	// Don't forget to fill this array

	protected $fillable = ['note','user_id','related_id','created_at','updated_at','type'];
	protected $table ='notes';


	public function writtenBy() 
		{
			return $this->belongsTo(User::class,'user_id')->with('person');
		}
		
	public function relatesToLocation() 
		{

			return $this->belongsTo(Location::class,'related_id');
		}
	public function relatesToLead() 
		{
			return $this->belongsTo(Lead::class,'related_id');
		}
	public function relatesToProject() 
		{
			return $this->belongsTo(Project::class,'related_id');

		}
	
}