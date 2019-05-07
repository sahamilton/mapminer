<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    public function getTeamActivityChart(array $data)
    {
     
      $chart= array();
      foreach($data['team'] as $team){
        if(isset($data[$team->id]['activities'])){
            $chart[$team->lastname.','.$team->firstname]=$data[$team->id]['activities'];
          }else{
            $chart[$team->lastname.','.$team->firstname]=0;
          }

      }
     return $this->getChartData($chart);
    }

    public function getTeamPipelineChart(array $data)
    {
      
      $chart= array();

      foreach($data['team'] as $team){
        if(isset($data[$team->id]['open'])){
          $chart[$team->lastname.", ". $team->firstname]=$data[$team->id]['open'];
        }else{
          $chart[$team->lastname.", ". $team->firstname]=0;
        }
      }
      return $this->getChartData($chart);
    }

    public function getTeamTop50Chart(array $data)
    {
      
      $chart= array();
      foreach($data['team'] as $team){
        if(isset($data[$team->id]['top50'])){
          $chart[$team->lastname.", ". $team->firstname]=$data[$team->id]['top50'];
        }else{
          $chart[$team->lastname.", ". $team->firstname]=0;
        }
      }
      return $this->getChartData($chart);
    }

    public function getWinRatioChart(array $data)
    {
      
      $chart= array();
      foreach($data['team'] as $team){
       
        if(isset($data[$team->id]) && ($data[$team->id]['won'] + $data[$team->id]['lost']>0)){
          $chart[$team->lastname.", ". $team->firstname] = 
          $data[$team->id]['won'] / ($data[$team->id]['won'] + $data[$team->id]['lost']);
        }else{
          $chart[$team->lastname.", ". $team->firstname] = 0;
        }
      }
      
      return $this->getChartData($chart);
    }

    public function getOpenLeadsChart(array $data)
    {
      $chart= array();
      foreach($data['team'] as $team){
        if(isset($data[$team->id]['leads'])){
            $chart[$team->lastname.','.$team->firstname]=$data[$team->id]['leads'];
          }else{
            $chart[$team->lastname.','.$team->firstname]=0;
          }

      }
    	return $this->getChartData($chart);
    }


    private function getChartData($chart)
    {
    	$data['chart']['keys'] = "'" . implode("','",array_keys($chart))."'";
	    $data['chart']['data'] = implode(",",$chart);
	    return $data;
    }
}
