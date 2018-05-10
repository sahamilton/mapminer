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

    	/*public function __construct($data){

    		if(isset($data['table'])){
	    		$this->table = $data['table'];
	    		$this->temptable = $this->table .'_import';

	   			if(isset($data['additionaldata'])){

	    			$this->additionaldata = $data['additionaldata'];
	    		}else{
	    			$this->additionaldata = [];
	    		}
	    		if(isset($data['filename'])){
	    			$this->importfilename = str_replace("\\","/",$data['filename']);
	    		}

       		}

    	}*/
		public function setFields($data){
			if(isset($data['additionaldata'])){

	    			$this->additionaldata = $data['additionaldata'];
	    		}else{
	    			$this->additionaldata = [];
	    		}
	    	// remove any additional data fields from input fields
	    	
	    	$data['fields'][key(array_intersect($data['fields'],array_keys($this->additionaldata)))]='@ignore';
	    	
    		$this->fields = implode(",",$data['fields']);
    		$this->table = $data['table'];
    		$this->temptable = $this->table . "_import";
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


    	public function import(){

      $this->createTemporaryImportTable();

      $this->_import_csv();

      $this->addCreateAtField();

      $this->updateAdditionalFields();

      $this->copyTempToBaseTable();

    		//$this->dropTempTable();

    		return true;
    	}


    	private function createTemporaryImportTable(){

			//Create the temporary table
			return $this->executeQuery("CREATE TEMPORARY TABLE ".$this->temptable." AS SELECT * FROM ". $this->table." LIMIT 0");

		}

		private function addCreateAtField(){
			// Import from the CSV file

			// make sure we bring the created at field across
			$this->fields.=",created_at";
			return $this->executeQuery("update ".$this->temptable." set created_at ='".Carbon::now()->toDateTimeString()."'");
		}

		private function updateAdditionalFields(){
		//Add the project source id
		//foreach ($this->additonaldata as)
		foreach ($this->additionaldata as $field=>$value){

				$this->fields.= ",".$field;
				return $this->executeQuery("update ".$this->temptable." set ". $field. " ='".$value."'");
			}
		}

		private function copyTempToBaseTable(){
			$this->fields = str_replace('@ignore,','',$this->fields);
			// COpy over to base table

			return $this->executeQuery("INSERT IGNORE INTO `".$this->table."` (".$this->fields.") SELECT ".$this->fields." FROM `".$this->temptable."`");
		}
		// Drop the temp table
		//
		private function dropTempTable(){
			return $this->executeQuery("DROP TABLE ".$this->temptable);
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



	$query = sprintf("LOAD DATA LOCAL INFILE '".$this->importfilename."' INTO TABLE ". $this->temptable." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$this->fields.");", $this->importfilename);


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

}
