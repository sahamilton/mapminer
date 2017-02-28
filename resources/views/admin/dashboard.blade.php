@extends('admin.layouts.default')

<?php $labels = $values = $n = $skip =NULL;
$cumulative = array();

foreach ($data['logins'] as $element) {
	if($skip > 10){
 		$labels.= "'" .$element->day_first_logged. "',";
 		$skip=0;
	}else{
		$labels.= "'',";
	}
	$skip++;
	// $values.= $element['logins'].",";
	 if($n > 0){
		 $cumulative[]=$element->logins + $cumulative[$n-1];
	 }else{
		 $cumulative[]=$element->logins;
	 }
 $n++;
}

$labels = substr($labels,0,-1);
//$values = substr($values,0,-1);

$total = implode(",",$cumulative);

$color=["#2c9c69","#00FF00","#FFFF99","#FF9933","#CC3300","#FF0000"];
$n=0;
$datastring=NULL;

foreach ($data['status'] as $status){
	$datastring.="{ value:". $status->count .",label:'". $status->status ."', color:\"".$color[$n]."\"},";
	$n++;
}
$datastring = substr($datastring,0,-1);

?>

{{-- Content --}}

@section('content')
<div class="container">
  <h2>Admin Dashboard</h2>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Usage</a></li>
    <li><a data-toggle="tab" href="#menu1">Activity</a></li>
    <li><a data-toggle="tab" href="#menu2">Account Health</a></li>
    <li><a data-toggle="tab" href="#menu3">Location Health</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>Usage</h3>
      	@include ('admin.partials.firstlogged')

		@include ('admin.partials.lastlogged')

    
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Account Activity</h3>
    @include('admin.partials.watchers')
 	@include('admin.partials.newNotes')
     
    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Account Health</h3>
   <@include ('admin.partials.nosalesnotes')
     @include ('admin.partials.duplicate')
    </div>
    <div id="menu3" class="tab-pane fade">
      <h3>Location Health</h3>
   @include('admin.partials.nocontacts')
   @include('admin.partials.nogeocode')
    </div>
  </div>
</div>














<script type="text/javascript" src="{{asset('assets/js/Chart.min.js')}}"></script>
<script>
var ctx = document.getElementById("barChart").getContext("2d");


var data = {
    labels: [{!! $labels !!}],
    datasets: [
        
		{
            label: "Cumulative Logins",
           fillColor: "rgba(44, 156, 105,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
			
    		
            data: [{!!$total!!}]
        }
    ]
};

new Chart(ctx).Bar(data);

var data = [
    {!!$datastring!!}

];
var canvas = document.getElementById("pieChart");
var ctx = canvas.getContext("2d");
new Chart(ctx).Doughnut(data);
</script>
@include('partials/_scripts')
@stop
