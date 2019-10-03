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
    /**
     * [setFields description]
     * 
     * @param [type] $data [description]
     *
     * @return [<description>] 
     */
    public function setFields($data)
    {
        if (isset($data['additionaldata'])) {

            $this->additionaldata = $data['additionaldata'];
        } else {
            $this->additionaldata = [];
        }
        // set null empty fields
        
        // remove any additional data fields from input fields
        
        $data['fields'][key(array_intersect($data['fields'], array_keys($this->additionaldata)))]='@ignore';
        
        $this->fields = implode(",", $data['fields']);  
                
        $this->table = $data['table'];

        if (! $this->temptable) {
            $this->temptable = $this->table . "_import";
        }

            

    }
    /**
     * [validateImport description]
     * 
     * @param [type] $fields [description]
     * 
     * @return [type]         [description]
     */
    public function validateImport($fields)
    {

        return array_diff($this->requiredFields, array_values($fields));
    }
    /**
     * [detectDuplicateSelections description]
     * 
     * @param [type] $fields [description]
     * 
     * @return [type]         [description]
     */
    public function detectDuplicateSelections($fields)
    {
        $realFields = array_diff(array_values($fields), array("@ignore"));

        if (count(array_unique($realFields)) < count($realFields)) {

            return array_unique(array_diff_key($realFields, array_unique($realFields)));
        }
        return false;
    }

    /**
     * [import description]
     * 
     * @param [type] $request [description]
     * 
     * @return [type]          [description]
     */
    public function import($request=null)
    {
        // set filename
      
        
        if (request()->filled('file')) {

            $this->importfilename = request('file');
        } else {

            $this->importfilename = str_replace("\\", "/", LeadSource::findOrFail(request('lead_source_id'))->filename);
        }
        
        
        if (! $this->dontCreateTemp) {
            $this->_createTemporaryImportTable();
        }
        $this->_truncateTempTable();
        $this->_importCSV();
        
        $this->_addLeadSourceRef($request);
        $this->_addCreateAtField();
        $this->_createPositon();
        $this->_updateAdditionalFields($request);
        if (! $this->dontCreateTemp) {
            
            $this->_copyTempToBaseTable();
            if (request()->filled('contacts')) {
                $this->_copyAddressIdBackToImportTable(request('lead_source_id'));
                $this->_copyContactsToContactsTable();

            }
            
            //$this->_nullImportRefField();

            //$this->_truncateTempTable();
        }
        

        //

        return true;
    }

   
    /**
     * [truncateImportTable description]
     * 
     * @return [type] [description]
     */
    private function truncateImportTable()
    {
       return $this->_executeQuery("TRUNCATE TABLE ". $this->temptable); 
    }
    /**
     * [_import_csv description]
     * 
     * @return [type] [description]
     */
    private function _importCSV()
    {
        $filename =  str_replace("\\","/",storage_path('app/'. $this->importfilename));
        
        $query = "LOAD DATA LOCAL INFILE '".$filename."' INTO TABLE ". $this->temptable." CHARACTER SET latin1 FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$this->fields.");";

        try {
            return  \DB::connection()->getpdo()->exec($query);
        } catch (Exception $e) {
             throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }
    /**
     * [_addLeadSourceRef description]
     * 
     * @param [type] $request [description]
     *
     * @return [type] [<description>]
     */
    private function _addLeadSourceRef($request)
    {
        
        return $this->_executeQuery("update ".$this->temptable." set lead_source_id='".request('lead_source_id')."'");
       
    }
    /**
     * [_addCreateAtField description]
     *
     * @return [type] [description]
     */
    private function _addCreateAtField()
    {
        // Import from the CSV file

        // make sure we bring the created at field across
        $this->fields.=",created_at";
        return $this->_executeQuery("update ".$this->temptable." set created_at ='". now()->toDateTimeString() . "'");
    }
    /**
     * [_createPositon description]
     * 
     * @return [type] [description]
     */
    private function _createPositon()
    {
        
        $this->_executeQuery("update ".$this->temptable." set position = POINT(lng, lat);");
    
        // $this->_executeQuery("update ".$this->temptable." set position = ST_GeomFromText(ST_AsText(position), 4326)");
    }
    /**
     * [_updateAdditionalFields description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _updateAdditionalFields(Request $request = null)
    {
        if ($request && request()->filled('additionaldata')) {
            foreach (request('additionaldata') as $key=>$data) {
                $this->_executeQuery("update ".$this->temptable." set " . $key . " = " . $data . ";");
            }
        }
        return true;
    }
    /**
     * [setNullFields description]
     * 
     * @param [type] $table [description]
     *
     * @return [type] [<description>]
     */
    public function setNullFields($table)
    {

        foreach ($this->nullFields as $field) {
            $this->_executeQuery("update ".$table." set " . $field . "= null where " . $field ."=''");
        }
        return true;
    }
    /**
     * [_createTemporaryImportTable description]
     * 
     * @return [type] [description]
     */
    private function _createTemporaryImportTable()
    {
        
        //Create the temporary table
        return $this->_executeQuery("TRUNCATE TABLE ". $this->temptable);
        //$this->_executeQuery("CREATE TABLE ".$this->temptable." AS SELECT * FROM ". $this->table." LIMIT 0");
        
        

    }
    /**
     * [createLeadSource description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    public function createLeadSource($data)
    {
        $lead_import_id = [
            
            'reference'=>date('YzHis'),
            'user_id'=>auth()->user()->id,
            'type'=>$data['type'],
            'description'=>$data['description'],
            'datefrom'=>Carbon::now(),
            'dateto'=>Carbon::now()->addYear(),
            'filename'=>$data['filename'],
        ];
        if (isset($data['company'])) {
            $company = Company::findOrFail($data['company']);
            $lead_import_id['source']= $company->companyname ." | ". date('YzHis');
        } else {
            $lead_import_id['source']="Import". date('YzHis');
        }
        return LeadSource::create($lead_import_id);
    }

   

    // we should dedupe here 
    /**
     * [_copyTempToBaseTable description]
     * 
     * @return [type] [description]
     */
    private function _copyTempToBaseTable()
    {
        
        $this->fields = str_replace('@ignore,', '', $this->fields).",lead_source_id,position";
        if (! $this->table = 'usersimport') {
            $this->fields = implode(",", array_diff(explode(",", $this->fields), $this->contactFields));
        }
        
       
        // Copy addresses over to base table
        $query ="INSERT IGNORE INTO `".$this->table."` (import_ref,".$this->fields.") SELECT id,".$this->fields." FROM `".$this->temptable."`";
    
        return $this->_executeQuery($query);
    }
    /**
     * [_copyAddressIdBackToImportTable description]
     * 
     * @param [type] $import [description]
     * 
     * @return [type]         [description]
     */
    private function _copyAddressIdBackToImportTable($import)
    {
        //update addresses_import,addresses set addresses_import.addressable_id = addresses.id where addresses.import_ref = addresses_import.id
        $query ="update " . $this->temptable. ",". $this->table . " set " . $this->temptable.".address_id = addresses.id where addresses.import_ref = ".$this->temptable.".id and ". $this->table . ".lead_source_id = '".$import."'";

        return $this->_executeQuery($query);
    }
    /**
     * [_copyContactsToContactsTable description]
     * 
     * @return [type] [description]
     */
    private function _copyContactsToContactsTable()
    {
        $query ="INSERT IGNORE INTO `contacts` (".implode(",", $this->contactFields).") 
        SELECT ".implode(",", $this->contactFields)." FROM `".$this->temptable."`";
        return $this->_executeQuery($query);
    }
    /**
     * [_nullImportRefField description]
     * 
     * @return [type] [description]
     */
    private function _nullImportRefField()
    {
        return $this->_executeQuery("update " . $this->table . " set import_ref = null");
    }
    // Drop the temp table
    //
    private function _dropTempTable()
    {
        //return $this->_executeQuery("DROP TABLE ".$this->temptable);
    }

    private function _truncateTempTable()
    {
        return $this->_executeQuery("TRUNCATE TABLE ".$this->temptable);
    }
    /**
     * [_executeQuery description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]        [description]
     */
    private function _executeQuery($query)
    {
        try {
            return \DB::statement($query);
        } catch (Exception $e) {
            throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }


    
    /*
    private function _truncateImport($table)
    {

        $query = 'truncate ' . $table;
        try {
            return  \DB::connection()->getpdo()->exec($query);
        } catch (Exception $e) {
            throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }  */
}
