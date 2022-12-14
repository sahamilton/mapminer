<?php
namespace App\Models;

trait Filters
{
   

    public function getSearchKeys($searchtable, $searchcolumn)
    {

        if (! session()->has('Search')) {
            return $keys = false;
        }

        $filtered= $this->isFiltered(['companies','locations'], ['vertical','segment','businesstype']);
        if (! $filtered) {
            return $keys = false;
        }
        // get the selected session keys

        $searchKeys = array_flatten(session('Search'));
        if (empty($searchKeys)) {
            return $keys =false;
        }
        

        // get all the keys for the selected table and columns
        if (is_array($searchcolumn)) {
            $tableKeys = SearchFilter::whereIn('searchtable', $searchtable)
                        ->whereIn('searchcolumn', $searchcolumn)
                        ->pluck('id')->toArray();
        } else {
            $tableKeys = SearchFilter::whereIn('searchtable', $searchtable)
                        ->pluck('id')->toArray();
        }
        
        $tableKeys = array_intersect($tableKeys, $searchKeys);

        return $tableKeys;
    }
    
    // Determine if the session filters have been set
    
    
    public function isFiltered(array $searchtable = null, array $searchcolumn = null, $vertical = null)
    {


        $filtered = false;

        // Get the current session filters
        if (! session()->has('Search')) {
                return $filtered;
        }

        
        $searchFilters= array_flatten(session('Search'));

        if (empty($searchFilters)) {
            return $filtered;
        }
        // first get the group filters
    
        if (! isset($vertical)) {
            $allFilters = SearchFilter::whereIn('searchtable', $searchtable)
                ->whereIn('searchcolumn', $searchcolumn)
                ->where('type', '=', 'group')
                ->pluck('id');
            
        //}elseif (isset($vertical)){
        } else {
            // If vertical is set see if all the segments of that vertical are set.

            $allFilters =   SearchFilter::where('parent_id', '=', $vertical)
                ->orWhere('id', '=', $vertical)
                ->orWhere(
                    function ($query) use ($searchtable, $searchcolumn) {
                        $query->whereIn('searchtable', $searchtable)
                            ->whereIn('searchcolumn', $searchcolumn)
                            ->where('canbenull', '=', 1);
                    }
                )
                ->orWhere(
                    function ($query) use ($searchtable, $searchcolumn) {
                    
                        $query->whereIn('searchtable', $searchtable)
                            ->where('inactive', '=', 1)
                            ->whereIn('searchcolumn', $searchcolumn)
                            ->where('type', '=', 'group');
                    }
                )
                ->orWhere(
                    function ($query) use ($searchtable, $searchcolumn) {
                
                        $query->where('searchtable', '=', 'locations')
                            ->where('inactive', '=', 1)->where('searchcolumn', '=', 'segment')
                            ->where('canbenull', '=', 1);
                    }
                )
                ->pluck('id');
            
            if (count($allFilters) == 0) {
                return $filtered;
            }
        
        /*}  else {
            
            $allFilters = SearchFilter::where('type','=','group')->pluck('id');
            */
        }

        
        // if all the group filters are not set in the session search filters
        // then the results are filtered
        $allFilters = $allFilters->toArray();
        
        if (count(array_intersect($searchFilters, $allFilters)) != count($allFilters)) {
            return $searchFilters;
        }
        return $filtered;
    }
    
    public function isNullable($searchKeys, array $columns = null)
    {
        
        $nullable=false;
        
        if (is_array($columns)) {
            $nullable = [];
            foreach ($columns as $column) {
                $nullFilters = SearchFilter::where('canbenull', '=', 1)->where('searchcolumn', '=', $column)->pluck('id');
                if (is_array($searchKeys) && count(array_intersect($searchKeys, $nullFilters)) >0) {
                    $nullable[$column] = true;
                } else {
                    $nullable[$column] = false;
                }
            }
        } else {
            $nullFilters = SearchFilter::where('canbenull', '=', 1)->pluck('id')->toArray();
            if (is_array($searchKeys) && count(array_intersect($searchKeys, $nullFilters)) >0) {
                $nullable = true;
            }
        }
        
            
        return $nullable;
    }
}
