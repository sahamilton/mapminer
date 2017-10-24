<?php 
 	$stats=array();
 	$stats[0]=0;
      $stats[1]=0;
      $stats[2]=0;
      $stats[3]=0;
      

foreach($leadsource->leads as $lead){
	if(count($lead->salesteam)>0){

		foreach ($lead->salesteam as $team){
			if(in_array($team->pivot->status_id,[1,2,4,5,6])){

				$stats[$team->pivot->status_id] +=1;
			}
			
		}
	}else{
		$stats[0] +=1;
	}
}
$statuses[0] = 'Unassigned';?>

@foreach ($stats as $key=>$value)
<li>{{$statuses[$key]}} - {{$value}}</li>
@endforeach
<?php $avg = null;?>
	@if($stats[0] > 0 && $stats[1] >0)
	<?php $avg =number_format($stats[1] / (count($leadsource->leads) - $stats[0]),2);?>
	@endif
<li>Average Offer - {{$avg}}</li>
<li>Offered to {{count($salesteams)}}</li>