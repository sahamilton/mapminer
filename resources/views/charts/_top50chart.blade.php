<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>

var barChart = new Chart(cttop50, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['team']['top50chart']['chart']['keys'] !!}],

      datasets:[{
        label: 'Top 50 Open Opportunities',
        data: [{!! $data['team']['top50chart']['chart']['data'] !!}],
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

