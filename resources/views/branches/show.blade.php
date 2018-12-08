@extends('site/layouts/maps')
@section('content')
<?php $type='map';?>

@include('branches/partials/_head')

     


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
          $('#map-container').storeLocator({'slideMap' : false, 
            'defaultLoc': true, 
            'defaultLat': '{{$data['branch']->address->lat}}', 
            'defaultLng' : '{{$data['branch']->address->lng}}',
            'dataLocation' :  '{{URL::to($data['urllocation'].'/'.$data['distance'].'/'.$data['latlng'].'/'.$data['company'])}}', 
            'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}'
            ,'listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );


        });
    </script>
    @include('partials/_copytoclipboard')
@endsection
