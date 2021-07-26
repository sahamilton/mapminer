<script>

var barChart = new Chart(ctnewleads, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['charts']['newleadschart']['keys'] !!}],

      datasets:[{
        label: 'New Leads',
        data: [{!! $data['charts']['newleadschart']['data'] !!}],
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

