<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>

var barChart = new Chart(ctw, 
{

    type: 'bar',

    resize:true,
	
    data:{

      labels: [{!! $data['team']['winratiochart']['keys'] !!}],

      datasets:[{
      	label: 'Team Win Loss %',
        data: [{!! $data['team']['winratiochart']['data'] !!}],
        backgroundColor: 'red'
      }]
      
    }

});
</script>
