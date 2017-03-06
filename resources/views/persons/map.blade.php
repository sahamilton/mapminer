@extends('site/layouts/maps')
@section('content')
<h2>Sales Team Members</h2>
{{isset($filtered )? "<h4 class='filtered'>Filtered</h4>" : ''}}
@include('partials/_showsearchoptions')
@include('partials/advancedsearch')
@include('maps/partials/_industry_keys')
<p><a href='{{route("person.index")}}'><i class="glyphicon glyphicon-th-list"></i> List view</a></p>

	
      


  </head>


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
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': {{$mylocation['lat']}}, 'defaultLng' : {{$mylocation['lng']}}, 'dataLocation' : "{{ route('salesmap')}}",'zoomLevel': 4, 'infowindowTemplatePath' : '{{asset('maps/templates/personwindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/person-list-description.html')}}'} );
        });
    </script>

@stop
