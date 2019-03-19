<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Http\Request;
class Imports extends Model
{
    	public $table;
    	public $temptable;
    	public $fields;
    	public $importfilename;
    	public $additionaldata;
    	public $nullFields;
    	public $contactFields = ['fullname','firstname','lastname','title','contactphone','email'];

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
    	// set filename
    	
        
    	if(request()->filled('filename')){
    		$this->importfilename = request('filename');
    	}else{
    		$this->importfilename = str_replace("\\","/",LeadSource::findOrFail(request('lead_source_id'))->filename);
    	}
    	
    
		if (! $this->dontCreateTemp){
			$this->createTemporaryImportTable();
		}
		$this->truncateTempTable();
		$this->_import_csv();
		$this->addLeadSourceRef($request);
		$this->addCreateAtField();
		$this->createPositon();
		$this->updateAdditionalFields();
		if (! $this->dontCreateTemp){

			$this->copyTempToBaseTable();
			if(request()->filled('contacts')){
				$this->copyAddressIdBackToImportTable(request('lead_source_id'));
				$this->copyContactsToContactsTable();
				
			// copy contacts to contacts
			}
			
			$this->nullImportRefField();

			$this->truncateTempTable();
		}


        //

        return true;
    }

   
    
    private function truncateImportTable()
    {
       return $this->executeQuery("TRUNCATE TABLE ". $this->temptable); 
    }
    
    private function addLeadSourceRef($request)
    {
        // need to fix the type field
        
        
        return $this->executeQuery("update ".$this->temptable." set lead_source_id='".request('lead_source_id')."'");
    }
    private function addCreateAtField()
    {
        // Import from the CSV file

        // make sure we bring the created at field across
        $this->fields.=",created_at";
        return $this->executeQuery("update ".$this->temptable." set created_at ='".now()->toDateTimeString()."'");
    }
    public function createPositon()
    {
        
       $this->executeQuery("update ".$this->temptable." set position = POINT(lng, lat);");
    
       // $this->executeQuery("update ".$this->temptable." set position = ST_GeomFromText(ST_AsText(position), 4326)");
    }
    private function updateAdditionalFields(Request $request)
    {
       foreach (request('additionaldata') as $key=>$data){
        $this->executeQuery("update ".$this->temptable." set " . $key . " = " . $data . ";");
       }
    
		return true;
		}

    public function setNullFields($table){

	    	foreach ($this->nullFields as $field){
	    		$this->executeQuery("update ".$table." set " . $field . "= null where " . $field ."=''");
	    	}
	    	return true;
    }
	private function createTemporaryImportTable(){
		
		//Create the temporary table
		return $this->executeQuery("TRUNCATE TABLE ". $this->temptable);
		//$this->executeQuery("CREATE TABLE ".$this->temptable." AS SELECT * FROM ". $this->table." LIMIT 0");
		
		

	}

	public function createLeadSource($data){
		$lead_import_id = [
			'source'=>"Import". date('YzHis'),
			'reference'=>date('YzHis'),
			'user_id'=>auth()->user()->id,
			'type'=>$data['type'],
			'description'=>$data['description'],
			'datefrom'=>Carbon::now(),
			'dateto'=>Carbon::now()->addYear(),
			'filename'=>$data['filename'],
		];
		
		return LeadSource::create($lead_import_id);
	}

   

// we should dedupe here 
    private function copyTempToBaseTable()
    {
        $this->fields = str_replace('@ignore,', '', $this->fields).",lead_source_id,position";
        $this->fields = implode(",", array_diff(explode(",", $this->fields), $this->contactFields));

        // Copy addresses over to base table
        $query ="INSERT IGNORE INTO `".$this->table."` (import_ref,".$this->fields.") SELECT id,".$this->fields." FROM `".$this->temptable."`";
    
        return $this->executeQuery($query);
    }

    private function copyAddressIdBackToImportTable($import)
    {
    //update addresses_import,addresses set addresses_import.addressable_id = addresses.id where addresses.import_ref = addresses_import.id
        $query ="update " . $this->temptable. ",". $this->table . " set " . $this->temptable.".address_id = addresses.id where addresses.import_ref = ".$this->temptable.".id and ". $this->table . ".lead_source_id = '".$import."'";

        return $this->executeQuery($query);
    }

    private function copyContactsToContactsTable()
    {
        $query ="INSERT IGNORE INTO `contacts` (".implode(",", $this->contactFields).") 
		SELECT ".implode(",", $this->contactFields)." FROM `".$this->temptable."`";
        return $this->executeQuery($query);
    }

    private function nullImportRefField()
    {
        return $this->executeQuery("update " . $this->table . " set import_ref = null");
    }
    // Drop the temp table
    //
    private function dropTempTable()
    {
        //return $this->executeQuery("DROP TABLE ".$this->temptable);
    }

    private function truncateTempTable()
    {
        return $this->executeQuery("TRUNCATE TABLE ".$this->temptable);
    }

    public function executeQuery($query)
    {
        try {
            return \DB::statement($query);
        } catch (Exception $e) {
            throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }



    public function _import_csv()
    {
        
        $query = sprintf("LOAD DATA LOCAL INFILE '".$this->importfilename."' INTO TABLE ". $this->temptable." CHARACTER SET latin1 FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$this->fields.");", $this->importfilename);


        try {
            return  \DB::connection()->getpdo()->exec($query);
        } catch (Exception $e) {
             throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }

    public function truncateImport($table)
    {

        $query = 'truncate ' . $table;
        try {
            return  \DB::connection()->getpdo()->exec($query);
        } catch (Exception $e) {
            throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }   
}
