<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>

<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$address->lat}},{{$address->lng}});
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var name = "{{$address->businessname}}";
    var address = "{{$address->street}}" + " {{$address->city}}" + " {{$address->state}}" + " {{$address->zip}}";
    var html =  name +  address;
	var marker = new google.maps.Marker({
	  position: myLatlng,
	  map: map,
	  title: name,
	  clickable: true
	});
	 
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>