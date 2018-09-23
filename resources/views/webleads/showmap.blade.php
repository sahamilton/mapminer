@extends('site/layouts/maps')
@section('content')

<h2>{{$data['title']}} Leads</h2>

@if($data['count']>=200)
@include('templeads.partials._limited')

@endif
<p><a href="{{$data['listviewref']}}"><i class="fa fa-th-list" aria-hidden="true"></i> List view</a></p>
  
<div id="store-locator-container"> 
	<div id="map-container" style="border:solid red 1px">
        <div id="loc-list"><p></p>

            <ul id="list"></ul>
        </div>
        <div id="map"></div>
      </div>
    </div>

<script>
    $(function() {
          $('#map-container').storeLocator({'slideMap' : false, 
            'defaultLoc': true, 
            'defaultLat': '{{$data['lat']}}', 
            'defaultLng' : '{{$data['lng']}}', 
            'dataLocation' : '{{URL::to($data['datalocation'])}}',
            'zoomLevel':{{$data['zoomLevel']}}, 
            'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
      $(function() {
        $('#cp2').colorpicker();
      });
     
        
      
        });
    </script>
    
@endsection
