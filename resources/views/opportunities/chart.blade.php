@extends('site.layouts.default')
@section('content')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawSeriesChart);

    function drawSeriesChart() {

      var data = google.visualization.arrayToDataTable([
        ['Branch', 'Sales Appts', 'Opportunities Won',     'Value'],
        {!! $data !!}
      ]);

      var options = {
        title: 'Correlation closes / won to sales appointments and opportunities',
        hAxis: {title: 'Sales Appts'},
        vAxis: {title: ' Won'},
        bubble: {textStyle: {fontSize: 11}}
      };

      var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div'));
      chart.draw(data, options);
    }
    </script>

    <div id="series_chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>


</div>
@endsection