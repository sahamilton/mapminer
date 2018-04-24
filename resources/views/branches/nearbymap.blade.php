@extends('site/layouts/maps')
@section('content')

<h2>Nearby Branches</h2>
<p>The closest branches that can serve the 


<a href="{{route('locations.show',$location->id)}}">{{$location->businessname}} </a>

location in {{$location->city}}<p>
<p><a href='{{route("assign.location",$location->id)}}'>
  <i class="fa fa-th-list" aria-hidden="true"></i> List view</a></p>
  @include('maps.partials._keys')
<div id="store-locator-container">
	<div id="map-container">
        <div id="loc-list"><p></p>

            <ul id="list"></ul>
        </div>
        <div id="map"></div>
      </div>
    </div>

<script>
	  $(function() {
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$location->lat}}', 'defaultLng' : '{{$location->lng}}', 'dataLocation' : '{{ route("shownearby.branchlocation",$location->id)}}','zoomLevel': 7, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-branch.html')}}','listTemplatePath' : '{{asset('maps/templates/branch-list-description.html')}}'} );
        });
    </script>
    
@stop
