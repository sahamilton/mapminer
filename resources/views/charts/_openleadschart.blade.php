<script>

var barChart = new Chart(ctleads, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['charts']['openleadschart']['keys'] !!}],

      datasets:[{
        label: 'Open Leads',
        data: [{!! $data['charts']['openleadschart']['data'] !!}],
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
          xAxes: [{
              display: true,
              ticks: {
                  autoSkip:false
                 
              }
          }]
      }
  }

});
</script>

