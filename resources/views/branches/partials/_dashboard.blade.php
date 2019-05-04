<div class="row">
  <div id="series_chart_div" 
   style="width: 45%;float:left;border:solid 1px #aaaaaa;margin:5px;"> 
   <h4>Wins vs Sales Appts</h4>
    @include('opportunities.partials._bubble')
  </div>
 <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
      <h4>Team Win Loss %</h4>
      <canvas id="ctw" width="300" height="300" style="float-right"></canvas>
        @include('opportunities.partials._winlosschart')
    </div>
  
    
 
</div>
<div class="row">
	<div style="width: 40%;float:left;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
      <h4>Team Activities</h4>
      <canvas id="ctb" width="300" height="300" style="float-right"></canvas>
        @include('opportunities.partials._teamchart')
    </div>
	<div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
		<h4>Team Top 50 Open Opportunities</h4>
           <canvas id="cttop50" width="300" height="300"></canvas>
           @include('opportunities.partials._top50chart')
	</div>
	          
</div>