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
     },
     options: {
     	scales: {
          xAxes: [{ 
            
             ticks: {
                  autoSkip:false,
                  beginAtZero: true   // minimum value will be 0.
              },
            }],
          yAxes: [{ 
             
             ticks: {
                  autoSkip:false,
                  
            	}, 
            }],
        }, // scales


     }

});
</script>
