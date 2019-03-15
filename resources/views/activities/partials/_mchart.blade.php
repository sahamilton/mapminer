<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>


var barChart = new Chart(ctx, 
{

    type: 'bar',

    resize:true,

    data:{
      labels: [{!! $data['activitychart']['keys'] !!}],

      datasets: [{!! $data['activitychart']['chartdata'] !!} ]
    },
    @if($data['branches']->count()>10)
    options: {
         legend: {
            display: false
         }
    }
    @endif

});
</script>
