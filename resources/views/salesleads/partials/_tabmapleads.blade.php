	<div id="map-container">
        <div id="loc-list"><p></p>

            <ul id="list"></ul>
        </div>
        <div id="map"></div>
      </div>
    </div>
	<script src="{{asset('maps/js/handlebars-1.0.0.min.js')}}"></script>
	<script src="https://maps.google.com/maps/api/js?key={{config('maps.api_key')}}"></script>
	<script src="{{asset('maps/js/jquery.storelocator.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap-colorpicker.min.js')}}"></script>
	<script>
	$(function() {
		$('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$leads->lat}}', 'defaultLng' : '{{$leads->lng}}', 'dataLocation' : '{{route('saleslead.mapleads',$leads->id)}}','zoomLevel':9, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
		
		$(function() {
			$('#cp2').colorpicker();
		});
	}); 

	</script>