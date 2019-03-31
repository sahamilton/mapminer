<script>


var barChart = new Chart(ctpipe, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['pipelinechart']['keys'] !!}],

      datasets:[{
        label: 'Branch Pipeline',
        data: [{!! $data['pipelinechart']['data'] !!}],
        backgroundColor: 'red'
      }]
    },
    options: {
      scales: {
          yAxes: [{
              display: true,
              ticks: {
                  
                  beginAtZero: true   // minimum value will be 0.
              }
          }]
      }

});
</script>

