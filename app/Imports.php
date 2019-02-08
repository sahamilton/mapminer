<?php

namespace App;
use Carbon\Carbon;


class Imports extends Model
{
    	public $table;
    	public $temptable;
    	public $fields;
    	public $importfilename;
    	public $additionaldata;
    	public $nullFields;


		public function setFields($data){
			if(isset($data['additionaldata'])){

	    			$this->additionaldata = $data['additionaldata'];
	    		}else{
	    			$this->additionaldata = [];
	    		}
	    	// set null empty fields
	    	
	    	// remove any additional data fields from input fields
	    	
	    	$data['fields'][key(array_intersect($data['fields'],array_keys($this->additionaldata)))]='@ignore';
	    	
    		$this->fields = implode(",",$data['fields']);  
    				
    		$this->table = $data['table'];

    		if(! $this->temptable){
    			$this->temptable = $this->table . "_import";
    		}
    		
    		$this->importfilename = str_replace("\\","/",$data['filename']);

    	}
    	public function validateImport($fields){

	   		return array_diff($this->requiredFields,array_values($fields));
    	}

    	public function detectDuplicateSelections($fields){
    		$realFields = array_diff(array_values($fields), array("@ignore"));

    		if(count(array_unique($realFields)) < count($realFields)){

    			return array_unique(array_diff_key( $realFields , array_unique( $realFields ) ));
    		}
    		return false;
    	}


    public function import($request=null){

		if (! $this->dontCreateTemp){
			$this->createTemporaryImportTable();
		}
		$this->_import_csv();
		$fileimport = $this->addFileImportRef($request);
		$this->addCreateAtField();
		$this->createPositon();
		$this->updateAdditionalFields();
		if (! $this->dontCreateTemp){
			$this->copyTempToBaseTable();
			$this->dropTempTable();
		}


		//

		return $fileimport;
		}

    public function setNullFields($table){

	    	foreach ($this->nullFields as $field){
	    		$this->executeQuery("update ".$table." set " . $field . "= null where " . $field ."=''");
	    	}
	    	return true;
    }
    	private function createTemporaryImportTable(){

			//Create the temporary table
			$this->executeQuery("DROP TABLE IF EXISTS ". $this->temptable);
			return $this->executeQuery("CREATE TABLE ".$this->temptable." AS SELECT * FROM ". $this->table." LIMIT 0");

		}
		private function addFileImportRef($request){
			// need to fix the type field
			$import_ref = ['ref'=>date('YzHis'),'user_id'=>auth()->user()->id,'type'=>'address','description'=>request('description')];
			$import = FileImport::create($import_ref);
			$this->executeQuery("update ".$this->temptable." set import_ref ='".$import->id ."'");
			return $import->id;
		}
		private function addCreateAtField(){
			// Import from the CSV file

			// make sure we bring the created at field across
			$this->fields.=",created_at";
			return $this->executeQuery("update ".$this->temptable." set created_at ='".now()->toDateTimeString()."'");
		}

		private function updateAdditionalFields(){
		//Add the project source id

		//foreach ($this->additionaldata as)
		

		foreach ($this->additionaldata as $field=>$value){

				$this->fields.= ",".$field;

				$this->executeQuery("update ".$this->temptable." set ". $field. " ='".$value."'");
			}
			return true;
		}


		private function copyTempToBaseTable(){
			$this->fields = str_replace('@ignore,','',$this->fields).",import_ref,position";
			
			// Copy over to base table
			$query ="INSERT IGNORE INTO `".$this->table."` (".$this->fields.") SELECT ".$this->fields." FROM `".$this->temptable."`";
		
			return $this->executeQuery($query);
		}
		// Drop the temp table
		//
		private function dropTempTable(){
			//return $this->executeQuery("DROP TABLE ".$this->temptable);
		 }



	public function executeQuery($query)
	{
		try{
			return \DB::statement($query);
		}
		catch (Exception $e)
		{
		 throw new Exception( 'Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);

		}

	}



   public function _import_csv()
	{

	$query = sprintf("LOAD DATA LOCAL INFILE '".$this->importfilename."' INTO TABLE ". $this->temptable." CHARACTER SET latin1 FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$this->fields.");", $this->importfilename);


	try {
		return  \DB::connection()->getpdo()->exec($query);
	}
	catch (Exception $e)
		{
		 throw new Exception( 'Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);

		}

	}

	public function truncateImport($table){

		$query = 'truncate ' . $table;
		try {
			return  \DB::connection()->getpdo()->exec($query);
		}
		catch (Exception $e)
		{
		 throw new Exception( 'Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);

		}
	}

	public function createPositon(){
		
		$this->executeQuery("update ".$this->temptable." set position = POINT(lat, lng);");
	
		$this->executeQuery("update ".$this->temptable." set position = ST_GeomFromText(ST_AsText(position), 4326)");
        
	}

}
