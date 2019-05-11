<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>

var numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  };
var roleWeekChart = new Chart(ctb, 
{
    type: 'bar',

    resize:true,

    data:{
      labels: ['{!!  implode("','",array_keys($data['team']['activitytypechart']) ) !!}'],
      datasets: [
       @foreach ($data['team']['activitytypechart'] as $activities) 
        @foreach($activities as $key=>$data)
        
        {
        label: '{!! $key !!}',
        data: '{{$data['data']}}',
        backgroundColor:'#{{$data['color']}}',
      },
      @endforeach
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
            ticks: {
                  autoSkip:false,
                  beginAtZero: true   // minimum value will be 0.
              },
            gridLines: { display: false },
            }],
          yAxes: [{ 
            stacked: true, 
            ticks: {
                  autoSkip:false,
                  beginAtZero: true ,  // minimum value will be 0.
            
              callback: function(value) { return numberWithCommas(value); },
            }, 
            }],
        }, // scales
        legend: {display: true}
    } // options
   }
);
</script>