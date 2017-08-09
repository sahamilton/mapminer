<?php
namespace App;
class Note extends Model {

	// Add your validation rules here
	public static $rules = [
		'note' => 'required'
	];
	// Don't forget to fill this array
	protected $fillable = ['note','user_id'];
protected $table ='notes';

	public function writtenBy() 
		{
			return $this->belongsTo(User::class,'user_id')->with('person');
		}
		
	public function relatesTo() 
		{
			return $this->belongsToMany(Location::class);
		}
	public function relatesToLead() 
		{
			return $this->belongsToMany(Lead::class);
		}
	public function relatesToProject() 
		{
			return $this->belongsToMany(Project::class);
		}
}