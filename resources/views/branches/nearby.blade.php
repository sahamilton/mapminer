@extends('site/layouts/maps')
@section('content')


<link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
 

<link rel="stylesheet" href="{{asset('maps/css/map.css')}}">
<div class="page-header">
<div class="float-right">

	 <p><a href="{{route('branches.index')}}">Show all branches</a></p>	
		</div>
        <h1>Nearby Branches</h1>
        <h4> within {{$data['distance']}} miles of the 
          <a href="{{{ route('branches.show',$data['branch']->id) }}}"> {{$data['branch']->branchname}} </a>
        branch  </h4> 
        <?php $data['address'] = $data['branch']->street ." ".$data['branch']->city ." ".$data['branch']->state;?>
        @include('maps/partials/_form')
        @include('partials.advancedsearch')
        </div>
 <div id="store-locator-container">
      <div id="page-header">

      </div>

      <div id="map-container">
        <div id="loc-list"><p></p>

            <ul id="list"></ul>
        </div>
        <div id="map"></div>
      </div>
    </div>


    <script>
	  $(function() {
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$data['branch']->lat}}', 'defaultLng' : '{{$data['branch']->lng}}', 'dataLocation' : '{{ route("nearby.branch", $data['branch']->id)}}', 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
        });
    </script>
@endsection
