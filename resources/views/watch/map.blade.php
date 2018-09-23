@extends('site/layouts/maps')

@section('content')


<h1>My Watch List</h1>


<p><a href="{{route('watch.index')}}" title="Review my watch list"><i class="fa fa-th-list" aria-hidden="true"></i> View My Watch List</a></p>
<p><a href="{{route('watch.export')}}" title="Download my watch list as a CSV / Excel file"><i class="fa fa-cloud-download" aria-hidden="true"></i></i> Download My Watch List</a> </p>

<?php if($data!= NULL) {?>
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
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$data['lat']}}', 'defaultLng' : '{{$data['lng']}}', 'dataLocation' : '{{route('api.watchmap')}}','zoomLevel':8, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
		  
		 
			  
		  
        });
    </script>
<?php }else{?>
<h2>You have no items in your watch list</h2>
<?php }?>

@endsection

