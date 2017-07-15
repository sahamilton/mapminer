<?php
namespace App;
class Watch extends Model {
	protected $table = 'location_user';
	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['user_id','location_id'];
	
	public function watchedBy() 
	{
		return $this->belongsToMany(User::class,'user_id');	
	}
	
	public function watching() 
	{
		return $this->hasMany(Location::class,'id','location_id');	
	}
	
	public function watchnotes() 
	{
		return $this->hasMany(Note::class,'location_id','location_id');	
	}
	
	public function exportWatchList($fields,$watchList){

		$output='';
		foreach ($fields as $field) {
			if(is_array($field)) {
				foreach($field as $key=>$value){
					$output.=$key.",";

				}
			 }else{
		 		$output.=$field.",";
			 }
		}
		$output.="\n";
	  	foreach ($watchList as $row) {
		  reset ($fields);
		  foreach ($fields as $field) {
			 if(is_array($field)) {
				foreach($field as $key=>$value){
					if($key == 'notes'){

						foreach ($row->$value as $watched){
							$string =$this->cleanseString($watched->note);
							$output.= $string."  |  ";


						}
					$output.=",";	
					}else{
						// remove non printing characters from string then remove any commas
						$string =$this->cleanseString($row->watching[0]->$key->$value);
						
						$output.=$string.",";
					}
				}
			 }else{
				if(!$row->watching[0]->$field) {
					$output.=",";
				}else{
					// remove non printing characters from string then remove any commas
					$string =$this->cleanseString($row->watching[0]->$field);
					$output.=$string.",";
				}
			 }

			  
		  }
		  $output.="\n";
			  
			  
		  }
	return $output;
	}
	
	private function cleanseString($string)
	{
		$string = preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$string);
		$string = str_replace(","," ", $string);
	return $string;
	}

	
}