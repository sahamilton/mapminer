@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>Construction Project</h2>
<p><a href="{{route('projects.index')}}">Return to all projects</a></p>
<h4><p>{{$project->project_title}}</h4>
<p><strong>Address:</strong>
<blockquote>{{$project->project_addr1}} /{{$project->project_addr2}}<br />{{$project->project_city}}, {{$project->project_state}} 
{{$project->project_zipcode}}
</blockquote>
<p><strong>People Ready Status:</strong>
@can('manage_projects')
  @include('projects.partials._manageprojects')
@else
@if(count($project->owner)>0)
    {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}</p>
  @else
    Open</p>
@endif
@endcan
<div id="map-container">
<div style="float:left;width:300px">

<p><strong>Type:</strong>

<p><strong>Dodge ref #:</strong>{{$project->dodge_repnum}}</p>

<p><strong>Category:</strong>
{{$project->structure_header}} / {{$project->project_type}}</p>
<p><strong>Stage:</strong>{{$project->stage}}</p>
<p><strong>Ownership:</strong>{{$project->ownership}}</p>
<p><strong>Bid Date:</strong>{{$project->bid_date}}</p>
<p><strong>Project Start:</strong>{{$project->start_yearmo}}</p>
<p><strong>Target Start:</strong>{{$project->target_start_date}}</p>
<p><strong>Target Completion:</strong>{{$project->target_comp_date}}</p>
<p><strong>Work type:</strong>{{$project->work_type}}</p>
<p><strong>Project Status:</strong>{{$project->status}}</p>

<p><strong>Total Project Value:</strong>{{$project->total_project_value}}k</p>
</div>

     <div id="map" style="height:300px;width:500px;border:red solid 1px"/>
@if(! $project->project_lat)
     	Unable to geocode this address
     @endif

</div>     <p>(Map accuracy: {{$project->accuracy}})</p>

</div>
@include('projects.partials._companylist')
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>
@if(isset($project->project_lat))
<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$project->project_lat}},{{$project->project_lng}});
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var name = "{{$project->title}}";
    var address = "{{$project->addr1}}" + " {{$project->city}}" + " {{$project->state}}" + " {{$project->zip}}";
    var html = address;
	var marker = new google.maps.Marker({
	  position: myLatlng,
	  map: map,
	  title: name,
	  clickable: true
	});
	 bindInfoWindow(marker, map, infoWindow, html);
}
function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }
google.maps.event.addDomListener(window, 'load', initialize);

    </script>
    @endif
@include('partials/_scripts')

@stop
