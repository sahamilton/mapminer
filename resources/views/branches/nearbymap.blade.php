@extends('site/layouts/maps')
@section('content')

<h2>Nearby Branches</h2>
<p>The closest branches that can serve the 


<a href="{{route('location.show',$data['location']['id'])}}">{{$data['location']['businessname']}} </a>

location in {{$data['location']['city']}}<p>
<p><a href='{{route("assign.location",$data['location']['id'])}}'>
  <i class="glyphicon glyphicon-th-list"></i> List view</a></p>
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
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$data['location']['lat']}}', 'defaultLng' : '{{$data['location']['lng']}}', 'dataLocation' : '{{ route("shownearby.branchlocation",$data['location']['id'])}}','zoomLevel': 7, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-branch.html')}}','listTemplatePath' : '{{asset('maps/templates/branch-list-description.html')}}'} );
        });
    </script>
    
@stop
