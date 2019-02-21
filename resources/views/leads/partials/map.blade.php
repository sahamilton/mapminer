<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>

<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$lead->lat}},{{$lead->lng}});
  var mapOptions = {
    zoom: 10,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var name = "{{$lead->company_name}}";
  var address = "{{$lead->city}}" + " {{$lead->state}}";
  var mylatlng = "{{$lead->lat ."," .$lead->lng}}";
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
 

	var leadmarker = new google.maps.Marker({
	  position: myLatlng,
	  map: map,
	  title: "{{$lead->businessname}}",
    icon: '//maps.google.com/mapfiles/ms/icons/green-dot.png',
	  clickable: true
	});
	 bindInfoWindow(leadmarker,  map, infoWindow, html);
}

function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

google.maps.event.addDomListener(window, 'load', initialize);

    </script>