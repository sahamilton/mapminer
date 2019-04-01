<div class="row">
  <div id="series_chart_div" 
   style="width: 45%;float:left;border:solid 1px #aaaaaa;margin:5px;"> 
   <h4>Wins vs Sales Appts</h4>
    @include('opportunities.partials._bubble')
  </div>

  
    <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
      <h4>Team Activities</h4>
      <canvas id="ctb" width="300" height="300" style="float-right"></canvas>
        @include('opportunities.partials._teamchart')
    </div>
 
</div>
<div class="row">
	<div style="width: 40%;float:left;border:solid 1px #aaaaaa;margin:5px;">
		<h4>Team Pipeline</h4>
           <canvas id="ctpipe" width="300" height="300"></canvas>
           @include('opportunities.partials._pipechart')
	</div>
	<div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
		<h4>Team Top 50 Opportunities</h4>
           <canvas id="cttop50" width="300" height="300"></canvas>
           @include('opportunities.partials._top50chart')
	</div>
	          
</div>