<script>


var barChart = new Chart(ctpipe, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['team']['pipelinechart']['keys'] !!}],

      datasets:[{!! $data['team']['pipelinechart']['data'] !!}],
        
     
    },
    options:{
      scales: {
          xAxes: [{ 
            stacked: true, 
            gridLines: { display: false },
            }],
          yAxes: [{ 
            stacked: true, 
           }],
        }
    }

});
</script>

