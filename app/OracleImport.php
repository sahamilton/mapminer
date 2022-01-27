<?php

namespace App;

use App\OracleSource;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use DB;


class OracleImport extends Imports
{
    public $table = 'oracle';
    public $tempTable = 'oracle_temp';
    public $additionalFields;
    public $types = ['refresh', 'adds', 'deletes'];

    public $dontCreateTemp= true;

    public $requiredFields = [
        'person_number',
        'first_name',
        'last_name',
        'primary_email',
        'business_title',
        'home_zip_code',
        'manager_name',
        'manager_email_address',

    ];


    public $fillable = [
        'person_number',
        'first_name',
        'last_name',
        'primary_email',
        'business_title',
        'job_code',
        'job_profile',
        'management_level',
        'home_zip_code',
        'location_name',
        'country',
        'cost_center',
        'service_line',
        'company',
        'manager_name',
        'manager_email_address',
        'source_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * [setDontCreateTemp description]
     * 
     * @param [type] $state [description]
     */
   
    public function import($request = null)
    {
        $this->tempTable = $this->table ."_temp";
        $this->importfilename = request('file');
        $this->_createSource($request);
        $this->_createTemporaryImportTable();
        switch(request('type')) {


        case'refresh':
                DB::statement("TRUNCATE TABLE ". $this->table);
                $this->additionalFields = [
                    'created_at'=>now(), 
                    'source_id'=>$this->source_id
                ];
                
            break;


        case 'adds':
                $this->additionalFields = [
                    'created_at'=>now(), 
                    'source_id'=>$this->source_id
                ];
                
               
              
            break;


        case 'deletes':
                
               $this->additionalFields = [
                    'deleted_at'=>now(), 
                    'source_id'=>$this->source_id
                ];
                
                
               

            break;
        }
        $this->_importCSV();               
        $this->_addAdditionalFields($request);
        $this->_lowerCaseEmails();
        $this->_copyFromTempToMainTable();
        $this->_updateDateFields();        
        return true;
    }

    private function _importCSV()
    {
        
        $filename =  str_replace("\\", "/", storage_path('/'. $this->importfilename));
        
        $query = "LOAD DATA LOCAL INFILE '".$filename."' INTO TABLE ". $this->tempTable." CHARACTER SET latin1 FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$this->fields.");";
      
        try {
            return  DB::connection()->getpdo()->exec($query);
        } catch (Exception $e) {
             throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }

    private function _createTemporaryImportTable()
    {
        
        $query = "CREATE TEMPORARY TABLE ". $this->tempTable . " SELECT * FROM ". $this->table . " LIMIT 0;";

        DB::statement($query);
        $query = "ALTER TABLE ". $this->tempTable ." CHANGE `current_hire_date` `current_hire_date` VARCHAR(20) NULL DEFAULT NULL";
        DB::statement($query);
    }

    private function  _copyFromTempToMainTable()
    {
        $query = "insert ignore into " 
        . $this->table ." (`" .
            implode("`,`", $this->fillable).
        "`)  select `" .
            implode("`,`", $this->fillable)."` FROM ".$this->tempTable;
        
        return DB::statement($query);
    }

    
    private function _createSource($request)
    {
        $data = [
            'user_id' => auth()->user()->id,
            'type'=>request('type'),
            'sourcefile' => $this->importfilename,
            ];
        $source = OracleSource::create($data);
        $this->source_id = $source->id;
    }

    private function _addAdditionalFields()
    {
        
        
        return DB::table($this->tempTable)->update($this->additionalFields);

    }

    private function _lowerCaseEmails()
    {
        return DB::table($this->tempTable)->update(
            [
                'manager_email_address' => DB::raw('lower(`manager_email_address`)'),
                'primary_email' => DB::raw('lower(`primary_email`)'),
            ]
        );
    }

    private function _updateDateFields()
    {
        $query = "update oracle, oracle_temp  
            set oracle.current_hire_date =  str_to_date(oracle_temp.current_hire_date, '%m/%d/%Y') 
            where oracle_temp.person_number = oracle.person_number";
        return DB::statement($query);
    }
}