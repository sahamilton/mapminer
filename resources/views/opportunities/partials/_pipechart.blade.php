<script>


var barChart = new Chart(ctpipe, 
{

    type: 'bar',

    resize:false,

    data:{
      labels: [{!! $data['pipeline']['keys'] !!}],

      datasets: [{!! $data['pipeline']['chartdata'] !!} ]
    },
   options: {
          animation: {
            duration: 10,
          },
    
          scales: {
            xAxes: [{ 
              stacked: true, 
              gridLines: { display: false },
              suggestedMin: 0,
              }],
            yAxes: [{ 
              stacked: true,
              ticks: {
                suggestedMin: 0,  
                } 
               
              }],
          }, // scales
          legend: {display: true}
      } // options
   }
);
</script>
