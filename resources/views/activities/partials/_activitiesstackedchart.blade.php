<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>
var ctx = document.getElementById("ctx").getContext("2d");
var numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  };
var roleWeekChart = new Chart(ctx, 
{
    type: 'bar',

    resize:true,

    data:{
      labels: ['{!! array_values($data['chart'])[0]['labels'] !!}'],
      datasets: [
       @foreach ($data['chart'] as $key=>$value)
       
      
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
</script>