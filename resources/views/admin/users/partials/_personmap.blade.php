<p><strong>Address:</strong>{{$user->person->fullAddress()}}</p>
@if(! $user->person->lat)
No address or unable to geocode this address
@else

<div id="map" style="height:300px;width:500px;border:red solid 1px"/></div>    
@endif
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>
@if(isset($user->person->lat))
<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$user->person->lat}},{{$user->person->lng}});
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);

	var name = "{{$user->person->postName()}}";
    var address = "{{$user->person->fullAddress()}}";
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
    @endif
