<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>

var barChart = new Chart(ctpipe, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['team']['pipelinechart']['keys'] !!}],

      datasets:[{
        label: 'Team Pipeline',
        data: [{!! $data['team']['pipelinechart']['data'] !!}],
        backgroundColor: 'red'
      }]
     
    }

});
</script>

