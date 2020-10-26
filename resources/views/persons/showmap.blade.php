@extends('site/layouts/maps')
@section('content')

<h4>Branches managed by {{$person->fullName()}}</h4>
<p>{{$person->userdetails->email}}</p>
<p>
<a href="{{route('person.show',$person->id)}}">

<i class="fas fa-th-list" aria-hidden="true"></i> List View</a>

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
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true,'defaultLat': '{{$person->manages->first()->lat}}', 'defaultLng' : '{{$person->manages->first()->lng}}', 'dataLocation' : "{{route('managed.branchmap',$person->id)}}",'zoomLevel': 7, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-branch.html')}}','listTemplatePath' : '{{asset('maps/templates/info-list-description.html')}}'} );
        });
    </script>
@endsection
