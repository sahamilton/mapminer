<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<script>

var barChart = new Chart(cttop50, 
{

    type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['team']['top50chart']['keys'] !!}],

      datasets:[{
        label: 'Top50 Open Opportunities',
        data: [{!! $data['team']['top50chart']['data'] !!}],
        backgroundColor: 'red'
      }]
    }

});
</script>

