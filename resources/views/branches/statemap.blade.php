@extends('site/layouts/maps')
@section('content')

<h2>{{$data['fullstate']}} State Branches</h2>
<h4> <a href="{{route('branches.index')}}" title="Show all branches" />Show all branches</a></h4>
<?php $route='branches.statemap';?>
@include('branches/partials/_state')
<p><a href="{{route('branches.statelist',$data['statecode'])}}"><i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>
  
<div id="store-locator-container"> @include('maps/partials/_keys')
	<div id="map-container">
        <div id="loc-list"><p></p>

            <ul id="list"></ul>
        </div>
        <div id="map"></div>
      </div>
    </div>

<script>
	  $(function() {
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$data['lat']}}', 'defaultLng' : '{{$data['lng']}}', 'dataLocation' : '{{ route("branch.statemap" , $data['statecode'])}}','zoomLevel': 7, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
        });
    </script>
    
@endsection
