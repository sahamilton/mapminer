<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    /**
     * [getTeamActivityChart description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getTeamActivityChart(array $data)
    {
     
      $chart= array();
      foreach($data['team'] as $team){
        if(isset($data[$team->id]['activities'])){
            $chart[$team->postName()]=$data[$team->id]['activities'];
          }else{
            $chart[$team->postName()]=0;
          }

      }
     return $this->getChartData($chart);
    }
    /**
     * [getTeamActivityByTypeChart description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getTeamActivityByTypeChart(array $data)
    {
$labels = $data['team']->map(function ($person){

  return $person->postName();
});
$labels = implode("','",$labels->toArray());
 $activitytypes = ActivityType::all();
  
     //dd($activitytypes);
      $chart= array();
      foreach($data['team'] as $team){
    
        $types = reset($data[$team->id]['activitiestype']);
        foreach($types as $type){
         
          foreach ($activitytypes as $acttype){
            // set the data
            if(array_key_exists($acttype->id, $type)){
              $chart[$acttype->activity]['data'][] = count($type[$acttype->id]);
            }else{
              $chart[$acttype->activity]['data'][]= 0;
            }
             $chart[$acttype->activity]['color']= "#" . $acttype->color;
            // set the labels
            //if(! isset( $chart[$team->postName()][$acttype->activity]['labels'] )){
              
           // }else{$chart[$team->postName()][$acttype->activity]['labels']= 
            $chart[$acttype->activity]['labels']=$labels; 
           // }
            
            }
        }
      } 
      
      foreach ($chart as $key=>$cht){
        $chart[$key]['data']=implode(",",$cht['data']);
        
      }
   
      return $chart;
    // return $this->getChartData($chart);
    }
    /**
     * [getTeamPipelineChart description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getTeamPipelineChart(array $data)
    {
      
      $chart= array();

      foreach($data['team'] as $team){
        if(isset($data[$team->id]['open'])){
          $chart[$team->postName()]=$data[$team->id]['open'];
        }else{
          $chart[$team->postName()]=0;
        }
      }
      return $this->getChartData($chart);
    }
    /**
     * [getTeamTop50Chart description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getTeamTop50Chart(array $data)
    {
      
      $chart= array();
      foreach($data['team'] as $team){
        if(isset($data[$team->id]['top50'])){
          $chart[$team->postName()]=$data[$team->id]['top50'];
        }else{
          $chart[$team->postName()]=0;
        }
      }
      return $this->getChartData($chart);
    }
    /**
     * [getWinRatioChart description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getWinRatioChart(array $data)
    {
      
      $chart= array();
      foreach($data['team'] as $team){
       
        if(isset($data[$team->id]) && ($data[$team->id]['won'] + $data[$team->id]['lost']>0)){
          $chart[$team->postName()] = 
          $data[$team->id]['won'] / ($data[$team->id]['won'] + $data[$team->id]['lost']);
        }else{
          $chart[$team->postName()] = 0;
        }
      }
      
      return $this->getChartData($chart);
    }
    /**
     * [getOpenLeadsChart description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getOpenLeadsChart(array $data)
    {
      $chart= array();
      foreach($data['team'] as $team){
        if(isset($data[$team->id]['leads'])){
            $chart[$team->postName()]=$data[$team->id]['leads'];
          }else{
            $chart[$team->postName()]=0;
          }

      }
    	return $this->getChartData($chart);
    }

    /**
     * [getChartData description]
     * @param  [type] $chart [description]
     * @return [type]        [description]
     */
    private function getChartData($chart)
    {
    	$data['chart']['keys'] = "'" . implode("','",array_keys($chart))."'";
	    $data['chart']['data'] = implode(",",$chart);
	    return $data;
    }
}
