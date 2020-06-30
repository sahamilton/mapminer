<div class="row">
  @if(auth()->user()->id == '1')
    @include('branches.partials._welcomehome_modal')
  @endif
  <div id="series_chart_div" 
   style="width: 80%;height:400px;border:solid 1px #aaaaaa;margin:5px;"> 
   <h4>Wins vs Sales Appts</h4>
   <p><b>"TAHA" Report</b></p>

    @include('charts._bubble')

  </div>
 </div>
<div class="row">
  <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
    <h4>Top 25 Open Opportunities</h4>
           <canvas id="ctTop25" width="300" height="300"></canvas>
           @include('charts._top25chart')
  </div>
  <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
      <h4>Win Loss %</h4>
      <canvas id="ctw" width="300" height="300" style="float-right"></canvas>
        @include('charts._winlosschart')
    </div>
    <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
    <h4>Leads</h4>
           <canvas id="ctleads" width="300" height="300"></canvas>
           @include('charts._openleadschart')
  </div>
	<div style="width: 40%;float:left;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
      <h4>Activities</h4>
      <canvas id="ctb" width="300" height="300" style="float-right"></canvas>
     
      
    </div>
	

  
	          
</div>