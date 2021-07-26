<script>


var barChart = new Chart(ctpipe, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['charts']['pipelinechart']['keys'] !!}],

      datasets:[{
        label: 'Branch Pipeline',
        data: [{!! $data['charts']['pipelinechart']['data'] !!}],
        backgroundColor: 'red'
      }]
    },
    options: {
      scales: {
          yAxes: [{
              display: true,
              ticks: {
                  autoSkip:false,
                  beginAtZero: true   // minimum value will be 0.
              }
          }],
           yAxes: [{
              display: true,
              ticks: {
                  autoSkip:false,
                  beginAtZero: true   // minimum value will be 0.
              },
          }]
      }
    }
});
</script>

