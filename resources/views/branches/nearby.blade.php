@extends('site/layouts/default')
@section('content')


<link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
 

<link rel="stylesheet" href="{{asset('maps/css/map.css')}}">
<div class="page-header">
<div class="pull-right">

	 <p><a href="{{route('branches.index')}}">Show all branches</a></p>	
		</div>
        <h1>Nearby Branches</h1>
        <h4> within {{$data['distance']}} miles of the 
          <a href="{{{ route('branch.show',$data['branches']->id) }}}"> {{$data['branches']->branchname}} </a>
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

    <script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="{{asset('maps/js/handlebars-1.0.0.min.js')}}"></script>
    <script src="https://maps.google.com/maps/api/js?client={{config('maps.api_key')}}&sensor=false"></script>
    <script src="{{asset('maps/js/jquery.storelocator.js')}}"></script>
    <script>
	  $(function() {
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$data['branches']->lat}}', 'defaultLng' : '{{$data['branches']->lng}}', 'dataLocation' : '{{ route("nearby.branch", $data['branches']->id)?d=".$data['distance'])}}', 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
        });
    </script>
@endsection
