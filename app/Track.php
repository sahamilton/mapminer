<?php
namespace App;
class Track extends \Eloquent {

	// Add your validation rules here
	public static $rules;
	protected $table = 'track';
	// Don't forget to fill this array
	public $fillable = ['user_id','lastactivity'];
	
	public $errors;
	
	
	
	
}