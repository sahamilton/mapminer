<script>

var numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  };
var activityTypeChart = new Chart(ctb, 
{
    type: 'bar',

    resize:true,

    data:{
      labels: ['{!!  reset($data['charts']['activitychart'])['labels'] !!}'],
      datasets: [
       @foreach ($data['charts']['activitychart'] as $key=>$value) 
       
        {
          label: '{!! $key !!}',
          data: [{{$value['data']}}],
          backgroundColor:'{{$value['color']}}',
      },
      @endforeach      
     ]
    },
    options: {
        animation: {
          duration: 5,
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
             ticks: {
                  autoSkip:false,
                  beginAtZero: true   // minimum value will be 0.
              },
            }],
          yAxes: [{ 
            stacked: true, 
             ticks: {
                  precision: 0,
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