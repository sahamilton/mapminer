<script>
    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        center: {
          lat: {{$branch->lat}},
          lng: {{$branch->lng}}
        },
        zoom: 13
      });

    var bounds = new google.maps.LatLngBounds();
    if (navigator.geolocation) {

      navigator.geolocation.getCurrentPosition(function(position) {
            
        var geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        
        var address = getAddress(geolocate);
        
        Geo.lat = position.coords.latitude;
        Geo.lng = position.coords.longitude;
        
        //populateHeader(Geo.lat, Geo.lng,address,'');
        var marker = new google.maps.Marker({
          position: geolocate,
          map: map,
          title: 'You are here!'
        });
       
        map.setCenter(geolocate);
            
          });
    } else {
        alert('bummer dude!');
    };
    var card = document.getElementById('pac-card');
    var input = document.getElementById('pac-input');
    var types = document.getElementById('type-selector');
    var strictBounds = document.getElementById('strict-bounds-selector');

    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

    var autocomplete = new google.maps.places.Autocomplete(input);

    // Bind the map's bounds (viewport) property to the autocomplete object,
    // so that the autocomplete requests use the current map bounds for the
    // bounds option in the request.
    autocomplete.bindTo('bounds', map);

    // Set the data fields to return when the user selects a place.
    autocomplete.setFields(
      ['address_components', 'geometry', 'icon', 'name']);

    var infowindow = new google.maps.InfoWindow();
    var infowindowContent = document.getElementById('infowindow-content');
    infowindow.setContent(infowindowContent);
    var markers = {!! $markers !!};
    for (var i = 0, length = markers.length; i < length; i++) {
         
        var data = markers[i],
            latLng = new google.maps.LatLng(data.lat, data.lng); 
        bounds.extend(latLng); 
        // Creating a marker and putting it on the map
        var marker = new google.maps.Marker({
          position: latLng,
          map: map,

          title: data.businessname
        });

        google.maps.event.addListener(marker, 'click', function(){
          infowindow.close(); // Close previously opened infowindow
          infowindow.setContent( "<div id='infowindow'>"+ data.businessname +"</div>");
          infowindow.open(map, marker);
      });
     
              
    };
    map.fitBounds(bounds);

    autocomplete.addListener('place_changed', function() {
      infowindow.close();
      marker.setVisible(false);
      var place = autocomplete.getPlace();
      if (!place.geometry) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        window.alert("No details available for input: '" + place.name + "'");
        return;
      }

      
      // If the place has a geometry, then present it on a map.
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17); // Why 17? Because it looks good.
      }
      marker.setPosition(place.geometry.location);
      marker.setVisible(true);
      
      var address = '';
      if (place.address_components) {
        address = [
          (place.address_components[0] && place.address_components[0].short_name || ''),
          (place.address_components[1] && place.address_components[1].short_name || ''),
          (place.address_components[2] && place.address_components[2].short_name || ''),
          (place.address_components[4] && place.address_components[4].
            short_name || ''),
          (place.address_components[6] && place.address_components[6].short_name || '')
        ].join(' ');
      }
      
      var link = "/mobile/searchaddress?address="+ address;
       
      infowindowContent.children['place-icon'].src = place.icon;
      infowindowContent.children['place-link'].textContent = place.name;
      infowindowContent.children['place-link'].href = link;
      infowindow.open(map, marker);
    });


    function checkAddress(place){
      //alert(JSON.stringify(place.geometry.location));
      // send address to back end
      // if response is address id
      // else check if new lead create
    }
    function getAddress(latLng) {
      
      var geocoder = new google.maps.Geocoder();
      
      if (geocoder) {
          geocoder.geocode({ 'latLng': latLng}, function (results, status) {
             if (status == google.maps.GeocoderStatus.OK) {
                var address = results[0].formatted_address;
                 
                  $('#pac-input').val(address);
              } else {
                $('#pac-input').val({{session('address')}});

            }
          });

      return address;
      };  
    };
    

}
</script>