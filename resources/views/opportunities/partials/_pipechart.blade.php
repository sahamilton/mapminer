<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>


var barChart = new Chart(ctpipe, 
{

    type: 'bar',

    resize:true,

    data:{
      labels: [{!! $data['pipeline']['keys'] !!}],

      datasets: [{!! $data['pipeline']['chartdata'] !!} ]
    }   

});
</script>
