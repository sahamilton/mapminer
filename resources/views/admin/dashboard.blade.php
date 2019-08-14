@extends('admin.layouts.default')

@php
$labels =null;
$cumulative = array();
@endphp

@foreach ($data['logins'] as $element)
  @if($loop->first || $loop->index % 3 === 0)
  
  @php  
    $labels.= "'" .$element->firstlogin. "',";
  @endphp

  @else
    @php   $labels.= "'',";@endphp
  @endif
   @if(! $loop->first)
      @php 
        $cumulative[]=$element->logins + $cumulative[$loop->index -1];
      @endphp
   
   @else
     @php
      $cumulative[]=$element->logins;
     @endphp
    @endif
@endforeach
@php
  $labels = substr($labels,0,-1);
  $total = implode(",",$cumulative);
  $datastring =implode(",",$data['status']->pluck('count')->toArray());
  $labelstring ="'".implode("','",$data['status']->pluck('status')->toArray())."'";
  $weekdata =implode(",",$data['weekcount']->pluck('login')->toArray());
  $weeklabels ="'".implode("','",$data['weekcount']->pluck('week')->toArray())."'";

@endphp


{{-- Content --}}



@section('content')

  <h2>{{auth()->user()->roles()->first()->display_name}} Dashboard</h2>
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" 
        data-toggle="tab" 
        href="#home" 
        aria-controls="home" aria-selected="true">Usage
    </a>
    </li>
     <li class="nav-item">
      <a class="nav-link" 
          data-toggle="tab" 
          href="#menu1" 
          aria-controls="menu1" 
          aria-selected="true">Activity
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" 
          data-toggle="tab" 
          href="#menu2" 
          aria-controls="menu2" 
          aria-selected="true">Account Health
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" 
          data-toggle="tab" 
          href="#menu3" 
          aria-controls="menu3" 
          aria-selected="true">Location Health
      </a>
    </li>

     <li class="nav-item">
      <a class="nav-link" 
          data-toggle="tab" 
          href="#menu4" 
          aria-controls="menu4" 
          aria-selected="true">Reports
      </a>
    </li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab" style="margin-bottom:80px">

      <h3>Usage</h3>
        @include('admin.partials._lastloggedselector')
        @include ('admin.partials.firstlogged')
        @include('admin.partials.weeklylogins')
        @include ('admin.partials.lastlogged')

        @include('admin.partials.roleweekly')
        @include('admin.partials.firsttimers')
        <div style="clear:both"></div>
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Account Activity</h3>

      
        @include('admin.partials.newNotes')
        @include('admin.partials.newLeadNotes')  
        @include('admin.partials.newProjectNotes') 
    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Account Health</h3>
        @include ('admin.partials.nosalesnotes')
        @include ('admin.partials.duplicate')
    </div>
    <div id="menu3" class="tab-pane fade">
      <h3>Location Health</h3>
      @include('admin.partials.nocontacts')
      @include('admin.partials.nogeocode')
    </div>
    <div id="menu4" class="tab-pane fade">
      <h3>Reports</h3>
      @include('reports.partials._index')
    </div>

  </div>


<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
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
              backgroundColor: "#3e95cd",
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
var ctx = document.getElementById("roleWeekChart").getContext("2d");
var numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  };
var roleWeekChart = new Chart(ctx, 
{
    type: 'bar',

    resize:true,

    data:{
      labels: ['{!! array_values($data['roleweekcount'])[0]['labels'] !!}'],
      datasets: [
       @foreach ($data['roleweekcount'] as $key=>$value)
       
      
      {

        label: '{{$key}}',
        data: [{!! $value['data'] !!}],
        backgroundColor: '{!! $value['color'] !!}' 
       },
      
      @endforeach
               
      ]
    },
 options: {
        animation: {
          duration: 10,
        },
        tooltips: {
          mode: 'label',
          callbacks: {
          label: function(tooltipItem, data) { 
            return data.datasets[tooltipItem.datasetIndex].label + ": " + numberWithCommas(tooltipItem.yLabel);
          }
          }
         },
        scales: {
          xAxes: [{ 
            stacked: true, 
            gridLines: { display: false },
            }],
          yAxes: [{ 
            stacked: true, 
            ticks: {
              callback: function(value) { return numberWithCommas(value); },
            }, 
            }],
        }, // scales
        legend: {display: true}
    } // options
   }
);

var ctx = document.getElementById("weekChart").getContext("2d");

var weekChart = new Chart(ctx, 
{
    type: 'bar',

    resize:true,

    data:{
      labels: [{!! $weeklabels !!}],

      datasets: [
          
          {
              label: "Weekly Logins",
              backgroundColor: "#ff0000",
              data:[{!!$weekdata!!}],
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

    resize:true,

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
@endsection

