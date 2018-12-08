<div id="map-container">
  <div style="float:left;width:300px">
  @include('construct.partials._projectdetails')
  </div>

<div id ="map" class="col" style="height:300px;width:500px;border:red solid 1px;margin-right:40px"></div>

</div>
@if(isset($project['location']['lat']))
@include('construct.partials._map')
@endif