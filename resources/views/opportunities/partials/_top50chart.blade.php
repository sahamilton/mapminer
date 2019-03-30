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

