<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>

var barChart = new Chart(ctTop25, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['team']['Top25chart']['chart']['keys'] !!}],

      datasets:[{
        label: 'Top 25 Open Opportunities',
        data: [{!! $data['team']['Top25chart']['chart']['data'] !!}],
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

