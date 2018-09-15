@extends('site/layouts/maps')
@section('content')


<link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
 

<link rel="stylesheet" href="{{asset('maps/css/map.css')}}">
<div class="page-header">
<div class="pull-right">

	 <p><a href="{{{ route('branches.index') }}}">Show all branches</a></p>	
		</div>
        <h1>Nearby Locations</h1>
        <h4> within {{$data['distance']}} miles of your location </h4> 
        <form action="{{route('shownearby.location')}}" method="get">
       <label>Show locations within
      
       <?php $values = $values = Config::get('app.search_radius');?>
       <select name='d' class="btn btn-mini" onchange='this.form.submit()'>
           @foreach($values as $value)
           	@if($value === $data['distance'])
            	<option selected value="{{$value}}">{{$value}} miles</option>
                @else
           		<option value="{{$value}}">{{$value}} miles</option>
                @endif
           @endforeach
        </select>
        <noscript><input type="submit" value="Submit"></noscript>
        </label>
</form>
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
	if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
} else {
    alert('It seems like Geolocation, which is required for this page, is not enabled in your browser. Please use a browser which supports it.');
}
	  function errorFunction() {
		   alert('It seems like Geolocation, which is required for this page, is not enabled in your browser. Please use a browser which supports it.');
	  }
	  
	  function successFunction(position) {
			var lat = position.coords.latitude;
    		var lng = position.coords.longitude;
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': lat, 'defaultLng' : lng, 'dataLocation' : "{{route('nearby.location')?d=$data['distance']}}&lat='+lat+'&lng='+lng+'", 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
        };
    </script>
@endsection