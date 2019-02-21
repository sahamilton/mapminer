
@if($user->person->lat && $user->person->lng)
<div id="teammap" class="float-right" style="height:400px;width:600px;border:red solid 1px"/></div> 
<div style="clear:both"></div>   


<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$user->person->lat}},{{$user->person->lng}});
  var mapOptions = {
    zoom: 10,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('teammap'), mapOptions);
	var name = "{{$user->person->postName()}}";
  var address = "{{$user->person->address}}";
  var html = address;
  @if(isset($salesrepmarkers))
  var salesreps = {!! $salesrepmarkers !!};
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
  @endif
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
    <p class="text-danger"><strong>No manager address or unable to geocode managers address</strong></p>
@endif