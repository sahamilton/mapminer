
@if($people->lat && $people->lng)
<div id="branchmap" class="float-right" style="height:400px;width:600px;border:red solid 1px"/></div> 
 



<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$people->lat}},{{$people->lng}});
  var mapOptions = {
    zoom: 10,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('branchmap'), mapOptions);
	var name = "{{$people->postName()}}";
  var address = "{{$people->address}}";
  var html = address;
  @if(isset($branchmarkers))
  var branches = {!! $branchmarkers !!};
  
  $.each(branches, function(key, data) {
      var branchlatLng = new google.maps.LatLng(data.lat, data.lng); 
      // Creating a marker and putting it on the map
      var branchmarker = new google.maps.Marker({
          position: branchlatLng,
          map: map,
          title: data.branchname,
          icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
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
<p class="text-danger"><strong>No address or unable to geocode this address</strong></p>

@endif