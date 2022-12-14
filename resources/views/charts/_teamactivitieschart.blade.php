<script>

var barChart = new Chart(ctb, 
{

    type: 'bar',

    resize:true,
	
    data:{

      labels: [{!! $data['team']['activities']['keys'] !!}],

      datasets:[{
      	label: 'Activities',
        data: [{!! $data['team']['activities']['data'] !!}],
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
