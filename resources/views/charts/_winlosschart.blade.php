<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>

var barChart = new Chart(ctw, 
{

    type: 'bar',

    resize:true,
	
    data:{

      labels: [{!! $data['team']['winratiochart']['chart']['keys'] !!}],

      datasets:[{
      	label: 'Win Loss %',
        data: [{!! $data['team']['winratiochart']['chart']['data'] !!}],
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
                  autoSkip:false,
                   beginAtZero: true 
                 
              }
          }]
      }
  }
});
</script>
