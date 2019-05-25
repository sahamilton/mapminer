<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    /**
     * [getTeamActivityChart description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    public function getTeamActivityChart(array $data)
    {
     
        $chart= array();
        foreach ($data['team'] as $team) {
            if (isset($data[$team->id]['activities'])) {
                $chart[$team->postName()]=$data[$team->id]['activities'];
            } else {
                $chart[$team->postName()]=0;
            }

        }
        return $this->_getChartData($chart);
    }
    /**
     * [getTeamActivityByTypeChart description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    public function getTeamActivityByTypeChart(array $data)
    {
        // Initialize
        $fullabels = $data['team']->map(
            function ($person) {
                return ['pid'=>$person->id,'name'=>$person->postName()];
            }
        );
        $fullabels = $fullabels->pluck('name', 'pid')->toArray();
        $labels = implode("','", $fullabels);
        $activitytypes = ActivityType::all();
        $types = $activitytypes->pluck('activity')->toArray();
        $chart= array();
        // Build array by team member of all activities by type
        $result = [];
        foreach ($data['team'] as $team) {
            foreach ($data[$team->id]['activitiestype'] as  $activity) {
                if (count($activity)>0) {
                    ksort($activity);
                    foreach ($activity as $key=>$act) {
                        // set key to activity name
                        $type = $activitytypes->where('id', $key)->first()->activity;
                        if (isset($result[$team->id][$type])) {
                            $result[$team->id][$type] += count($act);   
                        } else {
                            $result[$team->id][$type] = count($act);
                        }    
                    } 
                }  
            }
        }
  
        // fill array with necessary blank team members
        foreach (array_diff($data['team']->pluck('id')->toArray(), array_keys($result)) as $missing) {
            $result[$missing] = [];
        }
        // fill complete team array with missing activity types
        foreach ($result as $key=>$res) {
            $filled[$key] = $res;
            foreach (array_diff($types, array_keys($res)) as $missing) {
                $filled[$key][$missing] = 0;
            }
            ksort($filled[$key]);
        }

        // fill chart array by type, color, labels and data
        foreach ($this->_transpose($filled) as $key=>$result) {
            $color = $activitytypes->where('activity', $key)->first()->color;
            $chart[$key]['color']= "#" . $color;
            $chart[$key]['labels']=$labels; 
            $chart[$key]['data'] = implode(",", $result);
            
        }

        return $chart;
    }
    /**
     * Transpose array
     * 
     * @param array $array [description]
     * 
     * @return array $out [description]
     */
    private function _transpose(Array $array)
    {

        $out = array();
        foreach ($array as $key => $subarr) {
            foreach ($subarr as $subkey => $subvalue) {
                $out[$subkey][$key] = $subvalue;
            }
        }
        return $out;

    }
    /**
     * [getTeamPipelineChart description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    public function getTeamPipelineChart(array $data)
    {
      
        $chart= array();

        foreach ($data['team'] as $team) {
            if (isset($data[$team->id]['open'])) {
                $chart[$team->postName()]=$data[$team->id]['open'];
            } else {
                $chart[$team->postName()]=0;
            }
        }
        return $this->_getChartData($chart);
    }
    /**
     * [getTeamTop50Chart description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    public function getTeamTop50Chart(array $data)
    {
      
        $chart= array();
        foreach ($data['team'] as $team) {
            if (isset($data[$team->id]['top50'])) {
                $chart[$team->postName()]=$data[$team->id]['top50'];
            } else {
                $chart[$team->postName()]=0;
            }
        }
        return $this->_getChartData($chart);
    }
    /**
     * [getWinRatioChart description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    public function getWinRatioChart(array $data)
    {
      
        $chart= array();
        foreach ($data['team'] as $team) {
         
            if (isset($data[$team->id]) 
                && ($data[$team->id]['won'] + $data[$team->id]['lost'] > 0)
            ) {
                $chart[$team->postName()] 
                    =  $data[$team->id]['won'] 
                        / ($data[$team->id]['won'] + $data[$team->id]['lost']) * 100;
            } else {
                $chart[$team->postName()] = 0;
            }
        }
        
        return $this->_getChartData($chart);
    }
    /**
     * [getOpenLeadsChart description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    public function getOpenLeadsChart(array $data)
    {
        $chart= array();
        foreach ($data['team'] as $team) {
            if (isset($data[$team->id]['leads'])) {
                $chart[$team->postName()]=$data[$team->id]['leads'];
            } else {
                  $chart[$team->postName()]=0;
            }

        }
        return $this->_getChartData($chart);
    }

    /**
     * [_getChartData description]
     * 
     * @param [type] $chart [description]
     * 
     * @return [type]        [description]
     */
    private function _getChartData($chart)
    {
        $data['chart']['keys'] = "'" . implode("','", array_keys($chart))."'";
        $data['chart']['data'] = implode(",", $chart);
        return $data;
    }

    /**
     * [getTeamActivityByTypeChart description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    public function getBranchActivityByTypeChart(Object $data)
    {
        $labels = $data->pluck('day')->unique()->toArray();
        sort($labels);
        $labelstring = implode("','", $labels);
            
        $raw = $data->groupBy('activitytype_id');
        $activitytypes = ActivityType::all();
        $chart= array();
        foreach ($raw as $key=>$el) {
            $activity = $activitytypes->where('id', '=', $key)->first();
            $chart[$activity->activity]=[];
            foreach ($labels as $label) {
                if ($e = $el->where('day', '=', $label)->first()) {
                    $res[$activity->activity]['data'][] = $e->activities;
                } else {
                    $res[$activity->activity]['data'][] = 0;
                }
                
            }
            $chart[$activity->activity]['color'] = "#".$activity->color;
            $chart[$activity->activity]['data'] = implode(",", $res[$activity->activity]['data']);
            $chart[$activity->activity]['labels'] = $labelstring;
        }
        return $chart;

    }
}
