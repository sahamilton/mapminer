<?php
namespace App;

class Model extends \Eloquent {
	use Filters,HasRoles;
	public $userServiceLines;
	public $userVerticals;
	public $userRoles;
	
	public function getTableColumns($table=null){
		
	     		if(! $table){
	     			$table=$this->table;
	     		}
	     		
	   			return \DB::select( \DB::raw('SHOW COLUMNS FROM '.$table.''));
			}

	public function isValid($data)
		{
			$validation = Validator::make($data, static::$rules);
			
			if($validation->passes()) return true;
			
			$this->errors = $validation->messages();
			return false;
		}
		
	public function checkImportFileType($rules){
		// Make sure we have a file

			$file = Input::file('upload');
			// Make sure its a CSV file - test #1
			$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','text/x-c');
			if(!in_array($file->getMimeType(),$mimes)){
			 	return Redirect::back()->withErrors(['Only CSV files are allowed']);
			}		
			return $file;

	}
		public function checkImportFileStructure($filename){
			// map the file to the fields
			$datafile = fopen($filename, 'r');
			
			$data = fgetcsv($datafile);
			
			return $data;
			
		}
			
	/*public function _import_csv($filename, $table,$fields)
		{
		$filename = str_replace("\\","/",$filename);

		$query = sprintf("LOAD DATA LOCAL INFILE '".$filename."' INTO TABLE ". $table." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$fields.");", $filename);
		
		
		try {
			return  \DB::connection()->getpdo()->exec($query);
		}
		catch (Exception $e)
			{
			 throw new Exception( 'Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
			
			}
		
		}
		*/
	
	
	public function rawQuery($query,$error,$type){
		$result = array();
		try{
			switch ($type) {
				case 'insert':
					$result = \DB::insert( \DB::raw($query ) );
					break;
				case 'select':
					$result = \DB::select( \DB::raw($query ) );
				break;
				
				case 'update':
					$result = \DB::select( \DB::raw($query ) );
				break;

				
			
				default:
					$result = \DB::select( \DB::raw($query ) );
				break;
			}
			echo $query . ";<br />";		
		}
		catch (\Exception $e){
			echo $error . "<br />". $query;
			exit;
		}
		return $result;
	}
	
	/*
	 * Function export
	 *
	 * Create array of locations of logged in users watchlist
	 *
	 * @param fields arrary 
	 *         data array (collection)
	 *         filename string
	 * @return (array) csv results
	 */
	public function export ($fields,$data,$name='Export') {
		
		$filename = "attachment; filename=\"". time() . '-' .$name.".csv\"";
		$output='';
		foreach ($fields as $field) {
			
			if(! is_array($field)){
			 $output.=$field.",";
			}else{
				
				$output.= $field[key($field)].",";	
			}
				
		}
		 $output.="\n";
		  foreach ($data as $row) {
			  
			  reset ($fields);
			  foreach ($fields as $field) {
				if(! is_array($field)){
					if(! $row->$field) {
						$output.=",";
					}else{
						
				  		$output.=str_replace(","," ",strip_tags($row->$field)).",";
						
					}
				}else{
					$key = key($field);
					$element = $field[key($field)];
					
					if(! isset($row->$key->$element)) {
						$output.=",";
					}else{
				  		$output.=str_replace(","," ",strip_tags($row->$key->$element)).",";
						
					}
					
					
				}

				  
			  }
			  $output.="\n";
			  
			  
		  }

		  $headers = array(
			  'Content-Type' => 'text/csv',
			  'Content-Disposition' => $filename ,
		  );
	$results['headers'] = $headers;
	$results['output'] = $output;
	
	return $results;
 	 
	
	}
	
	
	/*
	 * Function export
	 *
	 * Create array of locations of logged in users watchlist
	 *
	 * @param fields arrary 
	 *         data array (collection)
	 *         filename string
	 * @return (array) csv results
	 */
	public function exportArray ($fields,$data,$name='Export') {
		
		$filename = "attachment; filename=\"". time() . '-' .$name.".csv\"";
		$output='';
		foreach ($fields as $field) {
			
			if(! is_array($field)){
			 $output.=$field.",";
			}else{
				
				$output.= $field[key($field)].",";	
			}
				
		}
		 $output.="\n";
		  foreach ($data as $row) {
			  
			  reset ($fields);
			  foreach ($fields as $field) {
				if(! is_array($field)){
					if(! $row[$field]) {
						$output.=",";
					}else{
						$cleanText = preg_replace( "/\r|\n/", "", $row[$field] );
				  		$output.=str_replace(","," ",strip_tags($cleanText)).",";
						
					}
				}else{
					$key = key($field);
					$element = $field[key($field)];
					
					if(! isset($row[$key][$element])) {
						$output.=",";
					}else{
						$cleanText = preg_replace( "/\r|\n/", "", $row[$key][$element] );
				  		$output.=str_replace(","," ",strip_tags($cleanText)).",";
						
					}
					
					
				}

				  
			  }
			  $output.="\n";
			  
			  
		  }

		  $headers = array(
			  'Content-Type' => 'text/csv',
			  'Content-Disposition' => $filename ,
		  );
	$results['headers'] = $headers;
	$results['output'] = $output;
	
	return $results;
 	 
	
	}
	

	
	public function getUserServiceLines()
	{
		
		$this->userServicelines= auth()->user()->serviceline()->pluck('servicelines.id')->toArray();

       session()->put('user.servicelines',$this->userServicelines) ;
       return $this->userServicelines;
	}

	

    public function getUserVerticals(){
        $this->userVerticals= auth()->user()->person->industryfocus()->pluck('search_filter_id')->toArray();
        session()->put('user.verticals',$this->userVerticals) ;
        return $this->userVerticals;
        
    }

    public function getUserRoles(){
        $this->userRoles= auth()->user()->roles->pluck('id')->toArray();
        session()->put('user.roles',$this->userRoles) ;
        return $this->userRoles;
        
    }
/*
	 * Function getWatchList
	 *
	 * Create array of locations of logged in users watchlist
	 *
	 * @param () none
	 * @return (array) mywatchlist
	 */
	public function getWatchList() {

		$watchlist = User::where('id','=',auth()->user()->id)->with('watching')->first();
		return $watchlist->watching->pluck('id')->toArray();
		/*foreach($watchlist as $watching) {
			foreach($watching->watching as $watched) {
				$mywatchlist[]=$watched->id;
			}
		}
		return $mywatchlist;*/
	}

	public function manyThroughMany($related, $through, $firstKey, $secondKey, $pivotKey)
   {
       $model = new $related;
       $table = $model->getTable();
       $throughModel = new $through;
       $pivot = $throughModel->getTable();

       return $model
           ->join($pivot, $pivot . '.' . $pivotKey, '=', $table . '.' . $secondKey)
           ->select($table . '.*')
           ->where($pivot . '.' . $firstKey, '=', $this->id);
   }
}