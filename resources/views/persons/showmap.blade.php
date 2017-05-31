@extends('site/layouts/maps')
@section('content')

<h4>Branches managed by {{$data['people']->firstname}} {{$data['people']->lastname}}</h4>
<p>{{$data['people']->email}}</p>
        
   

           <p><a href="{{route('person.show',$data['people']->id)}}"><i class="glyphicon glyphicon-th-list"></i> List View</a></p>	
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
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true,'defaultLat': '{{$data['lat']}}', 'defaultLng' : '{{$data['lng']}}', 'dataLocation' : "{{route('managed.branch',$data['people']->id)}}",'zoomLevel': 7, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-branch.html')}}','listTemplatePath' : '{{asset('maps/templates/info-list-description.html')}}'} );
        });
    </script>
@stop