     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawSeriesChart);

    function drawSeriesChart() {

      var data = google.visualization.arrayToDataTable([
        ['Branch', 'Sales Appts', 'Opportunities Won',     '$ Closes'],
        {!! $data['chart'] !!}
      ]);

      var options = {
        title: 'Correlation closes / won to sales appointments and opportunities',
       
        hAxis: {title: 'Sales Appts',
          viewWindow: {
              min: 0,
             
          },
          ticks: [25]
        },
       vAxis: {title: ' Won',
              viewWindow: {
                      min: 0,
                     
                  },
              ticks: [6]
        },
        height: 400,
        width: 400,
        bubble: {textStyle: {fontSize: 11}}
      };

      var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div'));
      chart.draw(data, options);
    }
    </script>