<?php
namespace App;
class News extends Model {

	// Add your validation rules here
	public $rules = array(
		 'title' => array('required','min:5'),
		 'news' => 'required',
		 'startdate' =>  array('required') ,
         'enddate' => 'required',
		 'slug' =>  array('unique:news','min:5')
	);

	// Don't forget to fill this array
	protected $fillable = ['title','news','startdate','enddate','slug','user_id'];
	public $dates =  ['created_at','updated_at','startdate','enddate'];

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
}