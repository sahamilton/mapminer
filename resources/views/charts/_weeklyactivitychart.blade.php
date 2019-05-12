<script>


var barChart = new Chart(ctx, 
{
 type: 'bar',

    resize:true,
  
    data:{

      labels: [{!! $data['activitychart']['keys'] !!}],

      datasets:[{
        label: 'Activities',
        data: [{!! $data['activitychart']['data'] !!}],
        backgroundColor: 'red'
      }]
     }

});
</script>
