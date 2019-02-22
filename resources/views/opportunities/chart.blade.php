@extends('site.layouts.default')
@section('content')
<div class="container">


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawSeriesChart);

    function drawSeriesChart() {

      var data = google.visualization.arrayToDataTable([
        ['Branch', 'Opportunities', 'Activities',     'Closes'],
        {!! $data !!}
      ]);

      var options = {
        title: 'Correlation of activity and opportunities',
        hAxis: {title: 'Opportunities'},
        vAxis: {title: 'Activities'},
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