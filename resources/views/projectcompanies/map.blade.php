
@extends('site.layouts.maps')
@section('content')


<h1>Nearby construction projects</h1>

@include('maps.partials._form')

<div>

  
   </div>
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
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 
          	'defaultLat': '{{$data['lat']}}', 
          	'defaultLng' : '{{$data['lng']}}', 
          	'dataLocation' : '{{$data['urllocation']}}',
          	'zoomLevel':{{$data['zoomLevel']}}, 
          	'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}',
          	'listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
		  $(function() {
        $('#cp2').colorpicker();
      });
		 
			  
		  
        });
    </script>


@endsection

