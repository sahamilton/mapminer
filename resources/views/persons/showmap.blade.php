@extends('site/layouts/maps')
@section('content')

<h4>Branches managed by {{$people->postName()}}</h4>
<p>{{$people->userdetails->email}}</p>
<p>
<a href="{{route('person.show',$people->id)}}">
<i class="glyphicon glyphicon-th-list"></i> List View</a>
</p>	
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
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true,'defaultLat': '{{$people->manages[0]->lat}}', 'defaultLng' : '{{$people->manages[0]->lng}}', 'dataLocation' : "{{route('managed.branchmap',$people->id)}}",'zoomLevel': 7, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-branch.html')}}','listTemplatePath' : '{{asset('maps/templates/info-list-description.html')}}'} );
        });
    </script>
@stop