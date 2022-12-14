<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>

<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({!! trim($lead->lat) !!},{!!trim($lead->lng)!!});
  var mapOptions = {
    zoom: 10,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var name = "{!! $lead->companyname!!}";
  var address = "{{trim($lead->city)}}" + ",{{trim($lead->state)}}";
 
  var salesreps = {!! $salesrepmarkers !!};
  var branches = {!! $branchmarkers !!};

  $.each(branches, function(key, data) {
      var branchlatLng = new google.maps.LatLng(data.lat, data.lng); 
      // Creating a marker and putting it on the map
      var branchmarker = new google.maps.Marker({
          position: branchlatLng,
          map: map,
          title: data.branchname,
          icon: '//maps.google.com/mapfiles/ms/icons/blue-dot.png',
          clickable: true
      });
      var branchCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.2,
            strokeWeight: 2,
            fillColor: '#00FF00',
            fillOpacity: 0.2,
            map: map,
            center: branchlatLng,
            radius: data.radius * 800 ,
          });

    });
  $.each(salesreps, function(key, data) {
      var saleslatLng = new google.maps.LatLng(data.lat, data.lng); 
      // Creating a marker and putting it on the map
      var salesmarker = new google.maps.Marker({
          position: saleslatLng,
          map: map,
          title: data.name,
          icon:'//maps.google.com/mapfiles/ms/icons/yellow-dot.png' ,
          clickable: true
      });

    });

	var leadmarker = new google.maps.Marker({
	  position: myLatlng,
	  map: map,
	  title: name + " " + address,
    icon: '//maps.google.com/mapfiles/ms/icons/green-dot.png',
	  clickable: true
	});
	 bindInfoWindow(leadmarker,  map, infoWindow);

}
function bindInfoWindow(marker, map, infoWindow) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

google.maps.event.addDomListener(window, 'load', initialize);

    </script>