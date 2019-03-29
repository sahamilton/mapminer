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

