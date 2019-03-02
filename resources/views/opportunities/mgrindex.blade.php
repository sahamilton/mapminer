@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>My Teams  Dashboard</h2>
 <nav>

  <div class="nav  nav-tabs"  id="nav-tab"  role="tablist">
    <a class="nav-item nav-link active"
    id="nav-dashboard-tab"
    data-toggle="tab"
    href="#dashboard"
    role="tab"
    aria-controls="nav-dashboard"
    aria-selected="true">
<strong>Dashboard</strong></a>

  <a class="nav-item nav-link "
    id="nav-summary-tab"
    data-toggle="tab"
    href="#summary"
    role="tab"
    aria-controls="nav-summary"
    aria-selected="true">
<strong>Summary</strong>
</a>

</div>
</nav>

<div class="tab-content" id="nav-tabContent">
    <div id="dashboard" class="tab-pane show active">
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawSeriesChart);

    function drawSeriesChart() {

      var data = google.visualization.arrayToDataTable([
        ['Branch', 'Sales Appts', 'Opportunities Won',     'Closes'],
        {!! $data['chart'] !!}
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
  </div>

    <div id="summary" class="tab-pane fade">
      @include('opportunities.partials._summary')
   </div>
</div>








</div>

@include('partials._scripts')
@endsection