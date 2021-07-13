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
           // $this->_createTemporaryImportTable();
        } 

        $this->_truncateTempTable();
        $this->_importCSV();
        
        $this->_addLeadSourceRef($request);
        $this->_addCreatedAtField();
        $this->_createPositon();
        $this->_updateAdditionalFields($request);
        //if (! $this->dontCreateTemp) {
            
        $this->_copyTempToBaseTable();
        $this->_copyAddressIdBackToImportTable(request('lead_source_id'));
        if (request()->filled('contacts')) {
                
            
            $this->_copyContactsToContactsTable();

        }
        if (request()->filled('branch_id')) {
                
            
            $this->_assignLeadstoBranches();

        }
            
            //$this->_nullImportRefField();

            //$this->_truncateTempTable();
        //}
        

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
        $filename =  str_replace("\\", "/", storage_path('/'. $this->importfilename));
 
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
    private function _addCreatedAtField()
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
        $lead_import_id['source'] = $this->_createLeadSourceName($data);
        $leadsource = LeadSource::create($lead_import_id);
       
        if ($data['serviceline']) {
            $leadsource->servicelines()->sync($data['serviceline']);
        }
        
        return $leadsource->id;
    }

    private function _createLeadSourceName($data)
    {
        if (isset($data['company'])) {
            $company = Company::findOrFail($data['company']);
            return $company->companyname ." | ". date('YzHis');
        } elseif (isset($data['newleadsourcename'])) {
            return $data['newleadsourcename'] ." Import". date('YzHis');
        } else { 
            return "Import". date('YzHis');
        }
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
        if ($this->table !== 'usersimport') {
            $skip = ["branch_id"];
            $this->fields = implode(",", array_diff(explode(",", $this->fields), $this->contactFields, $skip));
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
    private function _copyAddressIdBackToImportTable($leadsource_id)
    {
        

        $query ="update " . $this->temptable. ",". $this->table . " set " . $this->temptable.".address_id = ".$this->table.".id where ".$this->table.".import_ref = ".$this->temptable.".id and ". $this->table . ".lead_source_id = '".$leadsource_id."'";
    
        return $this->_executeQuery($query);
    }
    /**
     * [_copyContactsToContactsTable description]
     * 
     * @return [type] [description]
     */
    private function _copyContactsToContactsTable()
    {
        
       

        $contacts = \DB::table($this->temptable)->get()->map(
            function ($item) {
                return [
                    'address_id'=>$item->address_id,
                    'fullname'=>$item->fullname,
                    'firstname'=>$item->firstname,
                    'lastname'=>$item->lastname,
                    'title'=>$item->title,
                    'email'=>$item->email,
                    'contactphone'=>$item->contactphone,
                    'primary'=>1,
                  ];
            }
        );
        return Contact::insert($contacts->toArray());



       /* $query ="INSERT IGNORE INTO `contacts` (address_id,".implode(",", $this->contactFields).", primary, created_at) 
        SELECT address_id, ".implode(",", $this->contactFields).",1,'".now()->format('Y-m-d H:i:s') . "' FROM `".$this->temptable."`";
        
        return $this->_executeQuery($query);*/
    }
    /**
     * [_assignLeadstoBranches description]
     * 
     * @return [type] [description]
     */
    private function _assignLeadstoBranches()
    {
        
        $insert = \DB::table('addresses_import')
            ->get();
            dd($insert);
           /* ->map(
                function ($item) {
                       return [
                            'branch_id'=>$item->branch_id,
                            'address_id'=>$item->address_id,
                        ];
                }
            );
            dd($insert);*/
        return AddressBranch::insert($insert->toArray());
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
