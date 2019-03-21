<?php
namespace App;
class Watch extends Model {
	protected $table = 'location_user';
	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['user_id','location_id','address_id'];
	
	public function watchedBy() 
	{
		return $this->belongsTo(User::class,'user_id','id');	
	}
	
	public function watching() 
	{
		return $this->belongsTo(Address::class,'address_id','id');	
	}
	
	public function watchnotes() 
	{
		return $this->hasMany(Note::class,'related_id','location_id')->where('type','=','location');	
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

	/**
	 * Return watch list.
	 *
	 * @param  int  $id
	 * @return array watchList
	 */
	
	public function getMyWatchList($id=null) {
		if(! $id){
			$id = auth()->user()->id;
		}
	
		 return $this->with('watching','watching.company','watchnotes')
		
		->where("user_id","=", $id)
		->get();

		
	}
	
	
}