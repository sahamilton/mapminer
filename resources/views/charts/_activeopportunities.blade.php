<script>
var numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  };
var barChart = new Chart(ctao, 
{

    type: 'bar',

    resize:true,
	
    data:{

      labels: [{!! $data['charts']['pipelinechart']['keys'] !!}],

      datasets:[{
      	label: 'Active Opportunities Value',
        data: [{!! $data['charts']['pipelinechart']['data'] !!}],
        backgroundColor: 'red'
      }]
      
    },
    options: {
      tooltips: { 
           mode: 'label', 
           label: 'mylabel', 
           callbacks: { 
              label: function(tooltipItem, data) { 
                   return tooltipItem.yLabel.toLocaleString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); }, }, 
      }, 
      scales: {
          yAxes: [{
              display: true,
              ticks: {
                  autoSkip:false,
                  beginAtZero: true, // minimum value will be 0.
                  callback: function(value, index, values) {
                        return '$' + numberWithCommas(value);
                    }
              }
          }],
          xAxes: [{
              display: true,
              ticks: {
                  autoSkip:false,
                  beginAtZero: true 
                 
              }
          }]
      }
  }
});
</script>
