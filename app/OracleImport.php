<?php

namespace App;

use App\Oracle;
use Illuminate\Database\Eloquent\Model;

class OracleImport extends Imports
{
    public $table = 'oracle';
    public $tempTable = 'oracle_temp';

    public $types = ['refresh', 'adds', 'deletes'];

    public $dontCreateTemp= true;

    public $requiredFields = [
        'person_number',
        'first_name',
        'last_name',
        'primary_email',
        'business_title',
        'current_hire_date',
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
        'current_hire_date',
        'home_zip_code',
        'location_name',
        'country',
        'cost_center',
        'service_line',
        'company',
        'manager_name',
        'manager_email_address',
    ];

    /**
     * [setDontCreateTemp description]
     * 
     * @param [type] $state [description]
     */
   
    public function import($request = null)
    {
        $this->importfilename = request('file');
        switch(request('type')) {


            case'refresh':
                \DB::statement("TRUNCATE TABLE ". $this->table);
                $this->tempTable = $this->table;
                $this->_importCSV();
            break;


            case 'adds':

                //add =>import into temp table;
                $this->tempTable = $this->table."_temp";
                $this->_createTemporaryImportTable();
                
                $this->_importCSV();
                $this->_copyFromTempToMainTable();
                 //insert ignore into table
            break;


            case 'deletes':
                $this->tempTable = $this->table."_temp";
                $this->_createTemporaryImportTable();
                
                $this->_importCSV();
                $this->_deleteFromMainTable();

            break;
        }
                       

                
        return true;
    }

    private function _importCSV()
    {
        
        $filename =  str_replace("\\", "/", storage_path('/'. $this->importfilename));
        
        $query = "LOAD DATA LOCAL INFILE '".$filename."' INTO TABLE ". $this->tempTable." CHARACTER SET latin1 FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$this->fields.");";
      
        try {
            return  \DB::connection()->getpdo()->exec($query);
        } catch (Exception $e) {
             throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }

    private function _createTemporaryImportTable()
    {
        $query = "CREATE TEMPORARY TABLE ". $this->tempTable . " SELECT * FROM ". $this->table . " LIMIT 0;";

        return \DB::statement($query);
    }

    private function  _copyFromTempToMainTable()
    {
        $query = "insert ignore into " 
        . $this->table ." (`" .
            implode("`,`", $this->fillable).
        "`)  select `" .
            implode("`,`", $this->fillable)."` FROM ".$this->tempTable;
    
        return \DB::statement($query);
    }

    private function _deleteFromMainTable()
    {
        $query = "delete from " . $this->table . "
         where person_number in (
         select person_number from " . $this->tempTable.")";
        
        return \DB::statement($query);
    }
}