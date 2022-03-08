<script>

$(document).ready(function() {
		
		var markersArray = [];
		var map = null;
		var Geo={};
		var latlng = new google.maps.LatLng({{auth()->user()->position()}});
		if (navigator.geolocation) {
				
			var settings = {
				zoom: 10,
				center: latlng,
				mapTypeControl: true,
				scaleControl: true,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
				},
				navigationControl: true,
				navigationControlOptions: {
					style: google.maps.NavigationControlStyle.DEFAULT
				},
				mapTypeId: google.maps.MapTypeId.TERRAIN,
				backgroundColor: 'white'
			};
			map = new google.maps.Map(document.getElementById('map_canvas'), settings);
			navigator.geolocation.getCurrentPosition(function(position) {
        	
				var geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				var address = getAddress(geolocate);
				
				Geo.lat = position.coords.latitude;
            	Geo.lng = position.coords.longitude;
				
				populateHeader(Geo.lat, Geo.lng,address,'');
				var marker = new google.maps.Marker({
				  position: geolocate,
				  map: map,
				  title: 'You are here!'
			  });
					
					
					map.setCenter(geolocate);
					
				});
			   
			}else{
				$('#message').html('Enter the address you want to search from');
				
			}
	
  		google.maps.event.addListener(map, 'click', function(event){submitMapCoords( event.latLng.lat() , event.latLng.lng())});
		
		function submitMapCoords(lat,lng){
			latLng=lat+','+lng;
			//getAddress(latLng) 
			search = 'Lat:'+lat.toFixed(3) + ' Lng:' + lng.toFixed(3);
			distance ='100';
			populateHeader(lat, lng,search,distance);
			$('#selectForm').submit();
			
		}
	
		
		function getAddress(latLng) {
			let address = 'unknown'
			let geocoder = new google.maps.Geocoder();
			if (geocoder) {
				geocoder.geocode({ 'latLng': latLng}, function (results, status) {
				   if (status == google.maps.GeocoderStatus.OK) {
					var address = results[0].formatted_address; 
					$('#search').val(address);
				   }
				   else {
					$('#search').val(latLng);;
					
				   }
				})
        	} 
			return address;
		}
		
        function error(){
            console.log("Geocoder failed");
        }

        function populateHeader(lat, lng, search, distance){
         $('#lat:first').val(lat);
 		 $('#lng:first').val(lng);
		$("#search").val(search);
		if(distance == '100') {
		 $("#distance").val(distance);
		}else{
			
		 $("#distance").val('25');
		}
        }
	});	
		
	$("#address").change(function() {
	  $('#lat:first').val('');
	  $('#lng:first').val('');
	});	
		





</script>