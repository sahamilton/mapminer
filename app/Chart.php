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
        $labels = $data['team']->map(
            function ($person) {
                return $person->postName();
            }
        );
        $labels = implode("','", $labels->toArray());
        $activitytypes = ActivityType::all();
        $chart= array();
        foreach ($data['team'] as $team) {
            if (isset($data[$team->id]['activitiestype'])) {
                $types = reset($data[$team->id]['activitiestype']);
                foreach ($types as $type) {
                    foreach ($activitytypes as $acttype) {
                        // set the data
                        if (array_key_exists($acttype->id, $type)) {
                            $chart[$acttype->activity]['data'][] 
                                = count($type[$acttype->id]);
                        } else {
                            $chart[$acttype->activity]['data'][]= 0;
                        }
                        $chart[$acttype->activity]['color']= "#" . $acttype->color;
                        $chart[$acttype->activity]['labels']=$labels; 
                    }
                }
            } 
        }
        foreach ($chart as $key=>$cht) {
            $chart[$key]['data']=implode(",", $cht['data']);
        }
        return $chart;

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
                        / ($data[$team->id]['won'] + $data[$team->id]['lost']);
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
           
           /*
           "activitytypechart" => array:6 [▼
                "Sales Appointment" => array:3 [▼
                    "data" => "1,0,0,3,0,1,0"
                    "color" => "#FF0000"
                    "labels" => "Salinas, Salvador','McKenzie, Patrick','Windsor, Jeff','Lingar, Aaron','Roberts, Jami','Beardslee, Rachel','Mancell, Michon"
          ]*/
           /*
           "activitytypechart" => array:6 [▼
                "Sales Appointment" => array:3 [▼
                    "data" => "1,0,0,3,0,1,0"
                    "color" => "#FF0000"
                    "labels" => "2019-05-02,2019-05-03"
          ]*/
        
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
        

            
    
            
            $chart[$acttype->activity]['color']= "#" . $acttype->color;
            $chart[$acttype->activity]['labels']=$labels; 

  

        foreach ($chart as $key=>$cht) {
            $chart[$key]['data']=implode(",", $cht['data']);
        }
        return $chart;

    }
}
