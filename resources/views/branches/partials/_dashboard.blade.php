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
    <h4>Active Opportunities $Value</h4>
    <canvas id="ctao" width="300" height="300" style="float-right"></canvas>
      @include('charts._activeopportunities')
  </div>
  <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
    <h4># Active Leads</h4>
         <canvas id="ctactiveleads" width="300" height="300"></canvas>
         @include('charts._activeleadschart')

  </div>
  <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
    <h4># New Leads</h4>
         <canvas id="ctnewleads" width="300" height="300"></canvas>
         @include('charts._newleadschart')

  </div>
  @if(isset($data['charts']['activitytypechart']))
	<div style="width: 40%;float:left;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
    <h4>Activities by Branch</h4>
    <canvas id="ctb" width="300" height="300" style="float-right"></canvas>
      @include('charts._activitiesstackedchart')
      
  </div>
  @endif
  @if(isset($data['charts']['personactivitytypechart']))
   
    <div style="width: 40%;float:left;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
      <h4>Activities by Manager</h4>
      <canvas id="ctp" width="300" height="300" style="float-right"></canvas>
        @include('charts._personactivitiesstackedchart')
      
    </div>
	@endif

  
	          
</div>