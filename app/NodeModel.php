<?php
namespace App;

use \Baum\Node;

class NodeModel extends Node
{
    
    // 'parent_id' column name
    protected $parentColumn = 'parent_id';

      // 'lft' column name
      protected $leftColumnName = 'lft';

      // 'rgt' column name
      protected $rightColumnName = 'rgt';

      // 'depth' column name
      protected $depthColumnName = 'depth';

      // guard attributes from mass-assignment
      protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');

    public function isValid($data)
    {
        $validation = Validator::make($data, static::$rules);
        
        if ($validation->passes()) {
            return true;
        }
        
        $this->errors = $validation->messages();
        return false;
    }
    
    public function checkImportFileType($rules)
    {
        // Make sure we have a file

        $file = \Input::file('upload');
        // Make sure its a CSV file - test #1
        $mimes = ['application/vnd.ms-excel','text/plain','text/csv','text/tsv','text/x-c'];
        if (!in_array($file->getMimeType(), $mimes)) {
            return Redirect::back()->withErrors(['Only CSV files are allowed']);
        }
        return $file;
    }
    public function checkImportFileStructure($filename)
    {
        // map the file to the fields
        $datafile = fopen($filename, 'r');
        
        $data = fgetcsv($datafile);
        
        return $data;
    }
        
    public function _import_csv($filename, $table, $fields)
    {
        $filename = str_replace("\\", "/", $filename);

        $query = sprintf("LOAD DATA INFILE '".$filename."' INTO TABLE ". $table." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$fields.");", $filename);
    
        echo $query ."<br />";
    
        try {
            $result = DB::connection()->getpdo()->exec($query);
            return $result;
        } catch (Exception $e) {
            throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }
            
    public function fullAddress()
    {
        return $this->street.' ' .$this->address2.' ' .$this->city.' ' .$this->state.' ' .$this->zip;
    }
    
    
    
    public function rawQuery($query, $error, $type)
    {
        $result = [];
        try {
            switch ($type) {
                case 'insert':
                    $result = DB::insert(DB::raw($query));
                    break;
                case 'select':
                    $result = DB::select(DB::raw($query));
                    break;
                
                case 'update':
                    $result = DB::select(DB::raw($query));
                    break;

                
            
                default:
                    $result = DB::select(DB::raw($query));
                    break;
            }
            echo $query . ";<br />";
        } catch (\Exception $e) {
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
    public function export($fields, $data, $name = 'Export')
    {
        
        $filename = "attachment; filename=\"". time() . '-' .$name.".csv\"";
        $output='';
        foreach ($fields as $field) {
            if (! is_array($field)) {
                $output.=$field.",";
            } else {
                $output.= $field[key($field)].",";
            }
        }
         $output.="\n";
        foreach ($data as $row) {
            reset($fields);
            foreach ($fields as $field) {
                if (! is_array($field)) {
                    if (! $row->$field) {
                        $output.=",";
                    } else {
                        $output.=str_replace(",", " ", strip_tags($row->$field)).",";
                    }
                } else {
                    $key = key($field);
                    $element = $field[key($field)];
                    
                    if (! isset($row->$key->$element)) {
                        $output.=",";
                    } else {
                        $output.=str_replace(",", " ", strip_tags($row->$key->$element)).",";
                    }
                }
            }
            $output.="\n";
        }

          $headers = [
              'Content-Type' => 'text/csv',
              'Content-Disposition' => $filename ,
          ];
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
    public function exportArray($fields, $data, $name = 'Export')
    {
        
        $filename = "attachment; filename=\"". time() . '-' .$name.".csv\"";
        $output='';
        foreach ($fields as $field) {
            if (! is_array($field)) {
                $output.=$field.",";
            } else {
                $output.= $field[key($field)].",";
            }
        }
         $output.="\n";
        foreach ($data as $row) {
            reset($fields);
            foreach ($fields as $field) {
                if (! is_array($field)) {
                    if (! $row[$field]) {
                        $output.=",";
                    } else {
                        $cleanText = preg_replace("/\r|\n/", "", $row[$field]);
                        $output.=str_replace(",", " ", strip_tags($cleanText)).",";
                    }
                } else {
                    $key = key($field);
                    $element = $field[key($field)];
                    
                    if (! isset($row[$key][$element])) {
                        $output.=",";
                    } else {
                        $cleanText = preg_replace("/\r|\n/", "", $row[$key][$element]);
                        $output.=str_replace(",", " ", strip_tags($cleanText)).",";
                    }
                }
            }
            $output.="\n";
        }

          $headers = [
              'Content-Type' => 'text/csv',
              'Content-Disposition' => $filename ,
          ];
          $results['headers'] = $headers;
          $results['output'] = $output;
    
          return $results;
    }
    
    
    

    public function getUserServiceLines()
    {
        return $userServiceLines = Serviceline::whereIn('id', session('user.servicelines'))
        ->pluck('ServiceLine', 'id')
        ->toArray();
    }

    public function scopeServiceLine($query)
    {
        $servicelines = $this->getUserServiceLines();
        dd('node', $servicelines);
        return $query->whereHas('serviceline', function ($q) use ($servicelines) {
                $q->whereIn('serviceline', $servicelines);
        });
    }
}
