<script>

var barChart = new Chart(ctactiveleads, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['charts']['activeleadschart']['keys'] !!}],

      datasets:[{
        label: 'Active Leads',
        data: [{!! $data['charts']['activeleadschart']['data'] !!}],
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

