<h4>Wins vs Sales Appts</h4>
<div id="series_chart_div" 
  style="width: 600px; height: 500px;float:left"> 

  @include('opportunities.partials._bubble')
</div>

@if($data['branches']->count()< 10 )
  <div style="width: 400px; height: 300px;float:left" >
    <h4>Branch Activities</h4>
    <canvas id="ctx" width="400" height="400" ></canvas>
    @include('activities.partials._mchart')
  </div>
@else
  <div class="row" style="clear:both">
    <h4>Branch Activities</h4>
    @include('activities.partials._activitytable')
  </div>
@endif

<div class="row" style="clear:both;width:45%;float:left">

    <h4>Opportunities Won</h4>
    <canvas id="ctwon" width="400" height="400" ></canvas>
      @include('opportunities.partials._wonchart')
` </div>
<div class="row" style="width:45%;float:right">
    <h4>Pipeline</h4>
    <canvas id="ctpipe" width="400" height="400" ></canvas>
    @include('opportunities.partials._pipechart')

</div>
<div class="row" style="clear:both">
  <div class="col-sm-5">
    <h4>My Teams Logins</h4>
    @include('branches.partials._logins')

  </div>

</div>