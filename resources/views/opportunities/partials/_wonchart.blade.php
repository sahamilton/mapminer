<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>


var barChart = new Chart(ctwon, 
{

    type: 'bar',

    resize:true,

    data:{
      labels: [{!! $data['won']['keys'] !!}],

      datasets: [{!! $data['won']['chartdata'] !!} ]
    }   

});
</script>
