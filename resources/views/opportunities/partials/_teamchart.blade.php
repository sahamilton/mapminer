<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>

var barChart = new Chart(ctb, 
{

    type: 'bar',

    resize:true,
	
    data:{

      labels: [{!! $data['team']['activities']['chart']['keys'] !!}],

      datasets:[{
      	label: 'Team Activities',
        data: [{!! $data['team']['activities']['chart']['data'] !!}],
        backgroundColor: 'red'
      }]
      
    }

});
</script>
