<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>


var barChart = new Chart(ctx, 
{
 type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['activitychart']['keys'] !!}],

      datasets:[{
        label: 'Activities',
        data: [{!! $data['activitychart']['data'] !!}],
        backgroundColor: 'red'
      }]
     }
     ,
     options:{
 
	    scales: {
	        xAxes: [{
	            gridLines: {
	                offsetGridLines: true
	            },
	             ticks: {
	                  autoSkip:false,
	                   // minimum value will be 0.
	              },
	        }],
	        yAxes: [{
	            gridLines: {
	                offsetGridLines: true
	            },
	             ticks: {
	                  autoSkip:false,
	                  beginAtZero: true   // minimum value will be 0.
	              },
	        }]
	     }
    }

});
</script>
