<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>


var barChart = new Chart(ctx, 
{

    type: 'bar',

    resize:true,

    data:{
      labels: [{!! $data['team']['chart']['keys'] !!}],

      datasets:[{
        data: [{!! $data['team']['chart']['data'] !!}]
      }]
    }

});
</script
