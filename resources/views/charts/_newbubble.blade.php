@php

$data =[['label'=>'Branch 20 ','color'=>'red',
'values'=>['x'=>'100','y'=>'120','r'=>'10']],
['label'=>'Branch 40','color'=>'blue',
'values'=>['x'=>'80','y'=>'20','r'=>'50']]];
$labels = array_column($data, 'label');
$colors = array_column($data, 'color');
$values = array_column($data, 'values');

@endphp

<script>
var numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  };
var barChart = new Chart(ctbubble,
      {

        type: 'bubble',
        data: {
            labels:["{!! implode('","',$labels) !!}"],
            datasets: [
              @foreach ($data  as $branch) 
              { 
               label: '{{$branch['label']}}',
               data: [
                  {
                    @foreach ($branch['values'] as $axis=>$val)
                      {{$axis}}: {{$val}},
                    @endforeach 
                 }
                 @if(! $loop->last)
                  , 
                 @endif
                ],
              backgroundColor:'{{$branch['color']}}',

            }
            @if(! $loop->last)
                  , 
            @endif
        @endforeach
        ]},
        options: {
          tooltips: { 
            mode: 'label',
             callbacks: {
                label: function(tooltipItem, data) {
                   var label = data.datasets[tooltipItem.datasetIndex].label;
                   return label + ': (' + tooltipItem.xLabel + ', ' + tooltipItem.yLabel + ')';
                }
             }
          },
        
          scales: {
              yAxes: [{
                  display: true,
                  ticks: {
                      autoSkip:false,
                      beginAtZero: true   // minimum value will be 0.
                  }
              }],
              xAxes: [{
                  display: true,
                  ticks: {
                      autoSkip:false,
                     beginAtZero: true
                  }
              }]
          }
      }
    });

</script>
