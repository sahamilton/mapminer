<script>
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
</script>