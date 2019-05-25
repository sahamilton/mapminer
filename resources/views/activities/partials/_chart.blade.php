@if(isset($data['summary']) && count($data['summary']) > 0)

<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>


var barChart = new Chart(ctx, 
{

    type: 'bar',

    resize:true,

    data:{
      labels: [{!! $data['summary']['chart']['label'] !!}],

      datasets: [
          
          {
              label: "Weekly Activities",
              backgroundColor: "#ff0000",
              data:[{!! $data['summary']['chart']['data'] !!}],
              borderWidth: 1,
              fill:true,
          }
      ]
    },
},options = {
 legend: {
            position:'bottom',
         },
    scales: {
        xAxes: [{
            gridLines: {
                offsetGridLines: true
            },
             ticks: {
                  autoSkip:false,
                  beginAtZero: true   // minimum value will be 0.
              },
        }],
        yAxes: [{
            gridLines: {
                offsetGridLines: true
            },
             ticks: {
                  autoSkip:false,
                  beginAtZero: true   // minimum value will be 0.
              },
        }]
    }
});
</script>

@endif

</div>
