<?php
namespace App;

use \Carbon\Carbon;

class Model extends \Eloquent
{
    use Filters,HasRoles,PeriodSelector;
    public $userServiceLines;
    public $userVerticals;
    public $userRoles;
    
    /**
     * [scopeWithAndWhereHas description]
     * 
     * @param [type] $query      [description]
     * @param [type] $relation   [description]
     * @param [type] $constraint [description]
     * 
     * @return [type]             [description]
     */
    public function scopeWithAndWhereHas($query, $relation, $constraint) 
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }
    /**
     * [scopeWithCountAndWhereHas description]
     * 
     * @param [type] $query      [description]
     * @param [type] $relation   [description]
     * @param [type] $constraint [description]
     * 
     * @return [type]             [description]
     */
    public function scopeWithCountAndWhereHas($query, $relation, $constraint) 
    {
        return $query->whereHas($relation, $constraint)
            ->withCount([$relation => $constraint]);
    }
    /**
     * [getTableColumns description]
     * @param  [type]     $table [description]
     * @param  array|null $skip  [description]
     * @return [type]            [description]
     */
    public function getTableColumns($table, array $skip = null)
    {
        $query = "SHOW COLUMNS FROM ".$table;
        if ($skip) {
            $query = $query . " WHERE Field NOT IN ('". implode("','", $skip)."')";
        }
       
        return \DB::select(\DB::raw($query));
    }
    /**
     * [isValid description]
     * @param  [type]  $data [description]
     * @return boolean       [description]
     */
    public function isValid($data)
    {
            $validation = Validator::make($data, static::$rules);
            
        if ($validation->passes()) {
            return true;
        }
            
            $this->errors = $validation->messages();
            return false;
    }
    /**
     * [checkImportFileType description]
     * @param  [type] $rules [description]
     * @return [type]        [description]
     */
    public function checkImportFileType($rules)
    {
        // Make sure we have a file

            $file = Request::file('upload');
            // Make sure its a CSV file - test #1
            $mimes = ['application/vnd.ms-excel','text/plain','text/csv','text/tsv','text/x-c'];
        if (!in_array($file->getMimeType(), $mimes)) {
            return Redirect::back()->withErrors(['Only CSV files are allowed']);
        }
            return $file;
    }
    /**
     * [checkImportFileStructure description]
     * @param  [type] $filename [description]
     * @return [type]           [description]
     */
    public function checkImportFileStructure($filename)
    {
        // map the file to the fields
        $datafile = fopen($filename, 'r');
            
        $data = fgetcsv($datafile);
            
        return $data;
    }
    /**
     * [fullAddress description]
     * @return [type] [description]
     */
    public function fullAddress()
    {
        return $this->street.' ' .$this->address2.' ' .$this->city.' ' .$this->state.' ' .$this->zip;
    }
    
    /**
     * [rawQuery description]
     * @param  [type] $query [description]
     * @param  [type] $error [description]
     * @param  [type] $type  [description]
     * @return [type]        [description]
     */
    public function rawQuery($query, $error, $type)
    {
        $result = [];
        try {
            switch ($type) {
                case 'insert':
                    $result = \DB::insert(\DB::raw($query));
                    break;
                case 'select':
                    $result = \DB::select(\DB::raw($query));
                    break;
                
                case 'update':
                    $result = \DB::select(\DB::raw($query));
                    break;

                
            
                default:
                    $result = \DB::select(\DB::raw($query));
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
    /**
     * [scopeServiceLine description]
     * 
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeServiceLine($query)
    {
        $servicelines = $this->getUserServiceLines();
       
        return $query->whereHas('serviceline', function ($q) use ($servicelines) {
                $q->whereIn('serviceline', $servicelines);
        });
    }

    /**
     * [getUserServiceLines description]
     * @return [type] [description]
     */
    public function getUserServiceLines()
    {
        if (auth()->user()->hasRole('admin')) {
            $this->userServicelines = Serviceline::pluck('servicelines.id')->toArray();
        } else {
            $this->userServicelines= auth()->user()->serviceline()->pluck('servicelines.id')->toArray();
        }
        
        session()->put('user.servicelines', $this->userServicelines) ;
        return $this->userServicelines;
    }
    /**
     * [scopeFiltered description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeFiltered($query)
    {
        if (!$keys= $this->getSearchKeys(['companies'], ['vertical'])) {
            return $query;
        }
        return $query->whereIn('vertical', $keys);
    }
    /**
     * [getUserVerticals description]
     * @return [type] [description]
     */
    public function getUserVerticals()
    {
        $this->userVerticals= auth()->user()->person->industryfocus()->pluck('search_filter_id')->toArray();
        session()->put('user.verticals', $this->userVerticals) ;
        return $this->userVerticals;
    }
    /**
     * [getUserRoles description]
     * @return [type] [description]
     */
    public function getUserRoles()
    {
        $this->userRoles= auth()->user()->roles->pluck('id')->toArray();
        session()->put('user.roles', $this->userRoles) ;
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
    public function getWatchList()
    {

        $watchlist = User::where('id', '=', auth()->user()->id)->with('watching')->first();
        return $watchlist->watching->pluck('id')->toArray();
        /*foreach($watchlist as $watching) {
            foreach($watching->watching as $watched) {
                $mywatchlist[]=$watched->id;
            }
        }
        return $mywatchlist;*/
    }
    /**
     * [manyThroughMany description]
     * @param  [type] $related   [description]
     * @param  [type] $through   [description]
     * @param  [type] $firstKey  [description]
     * @param  [type] $secondKey [description]
     * @param  [type] $pivotKey  [description]
     * @return [type]            [description]
     */
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
    /**
     * [removeNullsFromSelect description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function removeNullsFromSelect($data)
    {
        
        foreach ($data as $key => $value) {
            if ($value[0]==null) {
                unset($data[$key]);
            }
        }
        return $data;
    }
    /**
     * [array_empty description]
     * @param  [type] $mixed [description]
     * @return [type]        [description]
     */
    public function array_empty($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $value) {
                if (! $this->array_empty($value)) {
                    return false;
                }
            }
        } elseif (! empty($mixed)) {
            return false;
        }
        return true;
    }


    public function scopeUserActions($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopePeriodActions($query, array $period)
    {
        return $query->whereBetween('created_at', [$period['from'], $period['to']])
            ->orWhereBetween('updated_at', [$period['from'], $period['to']]);
    }

    
    /**
     * [createOldColors description]
     * @param  [type] $num [description]
     * @return [type]      [description]
     */
    public function createOldColors($num)
    {
        $colors=[];
        $int = 0;
        // value must be between [0, 510]
        for ($int; $int<$num; $int++) {
            $i = 1/$num + ($int*(1/$num));
            $value = min(max(0, $i), 1) * 508;
            if ($value < 255) {
                $greenValue = 255;
                $redValue = sqrt($value) * 16;
                $redValue = round($redValue);
            } else {
                $redValue = 255;
                $value = $value - 255;
                $greenValue = 256 - ($value * $value / 255);
                $greenValue = round($greenValue);
            }
            
            $colors[$int]= "#" .  $this->decToHex($redValue). $this->decToHex($greenValue) . "00";
        }
        return $colors;
    }
    /**
     * [decToHex description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    private function decToHex($value)
    {
        if (strlen(dechex($value))<2) {
            return "0".dechex($value);
        } else {
            return dechex($value);
        }
    }
    /**
     * [createColors description]
     * @param  [type] $len [description]
     * @return [type]      [description]
     */
    public function createColors($len)
    {
      $frequency = .3;

      

      for ($i = 0; $i < $len; $i = ++$i)
      {
         $red = $this->decToHex((sin($frequency*$i + 0) * 127) + 128);
         $grn = $this->decToHex((sin($frequency*$i + 2) * 127) + 128);
         
         $blu = $this->decToHex((sin($frequency*$i + 4) * 127) + 128);
         

         $color[]="#".$red.$grn.$blu;
      }
      return $color;
    }
}
