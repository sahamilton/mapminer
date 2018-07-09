@if(! $project->lat)
Unable to geocode this address
@else
<div id="map" style="height:300px;width:500px;border:red solid 1px"/>

     	
   

</div>     <p>(Map accuracy: {{$project->accuracy}})</p>  
@endif
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>
@if(isset($project->lat))
<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$project->lat}},{{$project->lng}});
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var name = "{{$project->title}}";
    var address = "{{$project->addr1}}" + " {{$project->address->city}}" + " {{$project->address->state}}" + " {{$project->address->zip}}";
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