<?php
namespace App;

class Model extends \Eloquent {
	
public $userServiceLines;

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
		
public function _import_csv($filename, $table,$fields)
	{
		$filename = str_replace("\\","/",$filename);

	$query = sprintf("LOAD DATA INFILE '".$filename."' INTO TABLE ". $table." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$fields.");", $filename);
	
	
	try {
		return  \DB::connection()->getpdo()->exec($query);
	}
	catch (Exception $e)
		{
		 throw new Exception( 'Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
		
		}
	
	}
	
	
	
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
	
	
	public function getSearchKeys($searchtable, $searchcolumn)
	{

		if(! \Session::has('Search')){
			
			return $keys = FALSE;
		}

		$filtered= $this->isFiltered(['companies','locations'],['vertical','segment','businesstype']);
		if(! $filtered){
			
			return $keys = FALSE;
		}
		// get the selected session keys
		
		$searchKeys = array_flatten(\Session::get('Search'));
		if(empty($searchKeys)){
			return $keys =FALSE;
		}
		

		// get all the keys for the selected table and columns
		if(is_array($searchcolumn) ) {
			$tableKeys = SearchFilter::whereIn('searchtable',$searchtable)
						->whereIn('searchcolumn',$searchcolumn)
						->pluck('id')->toArray();
			
		}else{
			$tableKeys = SearchFilter::whereIn('searchtable',$searchtable)
						->pluck('id')->toArray();
		}
		
		$tableKeys = array_intersect($tableKeys,$searchKeys);

		return $tableKeys;
	}
	
	// Determine if the session filters have been set
	
	
	public function isFiltered(array $searchtable = NULL, array $searchcolumn = NULL, $vertical = NULL)
	{

		$filtered=FALSE;
		// Get the current session filters
		if (! \Session::get('Search'))
		{
			
   				return $filtered;
		}
		
		
		$searchFilters= array_flatten(\Session::get('Search'));
		if(empty($searchFilters)){
			return $filtered;
		}
		// first get the group filters
		if(! isset($vertical)){
			
			$allFilters = SearchFilter::whereIn('searchtable',$searchtable)
			->whereIn('searchcolumn',$searchcolumn)
			->where('type','=','group')
			->pluck('id');
			
		//}elseif (isset($vertical)){
		}else{
			// If vertical is set see if all the segments of that vertical are set.	
			
			$allFilters = 	SearchFilter::where('parent_id','=',$vertical)
			->orWhere(function($query) use($searchtable,$searchcolumn)
				{
					$query->whereIn('searchtable',$searchtable)
					->whereIn('searchcolumn',$searchcolumn)
					->where('canbenull','=',1);
					
					
				})
			->orWhere(function($query) use($searchtable,$searchcolumn)
				{
					
					$query->whereIn('searchtable',$searchtable)
					->where('inactive','=',1)
					->whereIn('searchcolumn',$searchcolumn)
					->where('type','=','group');
					
				})
			->orWhere(function($query) use($searchtable,$searchcolumn)
			{
				
				$query->where('searchtable','=','locations')
				->where('inactive','=',1)->where('searchcolumn','=','segment')
				->where('canbenull','=',1);
				
			})
				->pluck('id');
	
			if(count($allFilters) == 0) {
				return $filtered;
			}
		
		/*}  else {
			
			$allFilters = SearchFilter::where('type','=','group')->pluck('id');
			*/
		}
		
		// if all the group filters are not set in the session search filters
		// then the results are filtered
		
		if(count(array_intersect($searchFilters, $allFilters->toArray())) != count($allFilters->toArray())){
			
			return TRUE;
			
		}
		
		
	}
	
	public function isNullable($searchKeys, array $columns= NULL)
	{
		
		$nullable=FALSE;
		
		if(is_array($columns)){
			$nullable = array();
			foreach($columns as $column){
				
				$nullFilters = SearchFilter::where('canbenull','=',1)->where('searchcolumn','=',$column)->pluck('id');
				if(is_array($searchKeys) && count(array_intersect($searchKeys, $nullFilters)) >0 ){
			  
					$nullable[$column] = TRUE;
				}else{
					$nullable[$column] = FALSE;
				}
		
				
			}
		}else{
			$nullFilters = SearchFilter::where('canbenull','=',1)->pluck('id')->toArray();
			if(is_array($searchKeys) && count(array_intersect($searchKeys, $nullFilters)) >0 ){
			
				$nullable = TRUE;
			}
			
		}
		
			
		return $nullable;
	}
	
	public function getUserServiceLines()
	{
		
		if (session()->has('user.servicelines')){
			$this->userServiceLines = session()->get('user.servicelines');
			return session()->get('user.servicelines');
		}

		return $this->currentUserServicelines();
	}

	public function currentUserServicelines(){
       $user = auth()->user();
       $userServicelines= $user->serviceline()->pluck('servicelines.id')->toArray();
       session()->put('user.servicelines',$userServicelines) ;
       $this->userServiceLines = session()->get('user.servicelines');
       return $userServicelines;
    }

    public function getUserVerticals(){
        $user = auth()->user()->with('person')->firstOrFail();
        $userVerticals= $user->person->industryfocus()->pluck('search_filter_id')->toArray();
        session()->put('user.verticals',$userVerticals) ;
        return $userVerticals;
        
    }

}