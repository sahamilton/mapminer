
@if($person->lat && $person->lng)

 



<script type="text/javascript">
  

function initialize() {
  var myLatlng = new google.maps.LatLng({{$person->lat}},{{$person->lng}});
  var mapOptions = {
    zoom: 10,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('branchmap'), mapOptions);
	var name = "{{$person->fullName()}}";
  var address = "{{$person->address}}";
  var html = address;
  
	var marker = new google.maps.Marker({
	  position: myLatlng,
	  map: map,
	  title: name,
	  clickable: true
	});
	 bindInfoWindow(marker, map, infoWindow, html);
}
function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }
google.maps.event.addDomListener(window, 'load', initialize);

    </script>
@else
<p class="text-danger"><strong>No address or unable to geocode this address</strong></p>

@endif