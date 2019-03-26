<h4>Wins vs Sales Appts</h4>
  <div id="series_chart_div" 
    style="width: 600px; height: 500px;float:left"> 
    @include('opportunities.partials._bubble')
  </div>

  @if($data['branches']->count() > 10 )
    <div style="width: 400px; height: 300px;float:left" >
      <h4>Team Activities</h4>
      <canvas id="ctb" width="400" height="400" ></canvas>
        @include('opportunities.partials._teamchart')
    </div>
  @else
    <div style="width: 400px; height: 300px;float:left" >
      <h4>Branch Activities</h4>
      <canvas id="ctx" width="400" height="400" ></canvas>
      @include('activities.partials._mchart')
    </div>
  @endif
</div>
