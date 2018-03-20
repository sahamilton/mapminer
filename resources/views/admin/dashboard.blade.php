@extends('admin.layouts.default')

<?php 
$labels =null;
$cumulative = array();?>

@foreach ($data['logins'] as $element)
  @if($loop->first || $loop->index % 3 === 0)
	
 	<?php 	$labels.= "'" .$element->firstlogin. "',";?>
	@else
<?php   $labels.= "'',";?>
  @endif
	 @if(! $loop->first)
   		 <?php $cumulative[]=$element->logins + $cumulative[$loop->index -1];?>
   
	 @else
		 <?php $cumulative[]=$element->logins;?>
    @endif
@endforeach
<?php $labels = substr($labels,0,-1);
//$values = substr($values,0,-1);

$total = implode(",",$cumulative);

$labelstring ="'" .implode("','",array_keys($data['status']->keyBy('status')->toArray()))."'";

$datastring =implode(",",array_keys($data['status']->keyBy('count')->toArray()));
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

        @include('admin.partials.firsttimers')
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Account Activity</h3>
    @include('admin.partials.watchers')
 	  @include('admin.partials.newNotes')
    @include('admin.partials.newLeadNotes')  
    @include('admin.partials.newProjectNotes') 
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




<script type="text/javascript" 
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>
var ctx = document.getElementById("barChart").getContext("2d");

var barChart = new Chart(ctx, 
{
    type: 'line',
    data:{
      labels: [{!! $labels !!}],

      datasets: [
          
          {
              label: "Cumulative Logins",
              backgroundColor: ["#3e95cd"],
              data:[{!!$total!!}],
              borderWidth: 1,
              fill:true,
          }
      ]
    },
},options = {
    scales: {
        xAxes: [{
            gridLines: {
                offsetGridLines: true
            }
        }]
    }
});

new Chart(document.getElementById("pieChart"), {
    type: 'doughnut',
    data: {
     
      datasets: [
        {
          label: "Number of Users",
          backgroundColor: ["{!! implode('","',$color)!!}"],
          data: [{!! $datastring !!}]
        }
      ],
      labels: [{!! $labelstring !!}]
    },
    options: {
      title: {
        display: false,
        text: 'Number of users by date of last login'
      },
      legend:{
        display:false,
      }
    }
});
</script>
@include('partials/_scripts')
@stop
