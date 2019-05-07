<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>

var barChart = new Chart(ctleads, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['team']['openleadschart']['chart']['keys'] !!}],

      datasets:[{
        label: 'Open Leads',
        data: [{!! $data['team']['openleadschart']['chart']['data'] !!}],
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
  }

});
</script>

