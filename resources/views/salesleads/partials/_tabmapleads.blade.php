	<div id="map" style="border:solid 1px red"></div>
    <script>
     
      // First, create an object containing LatLng and details for each branch.
      var leadmap = {
        
      @foreach ($leads->salesleads as $lead)
            '{{$lead->businessname}}' : {
              center : {lat: {{$lead->lat}}, lng: {{$lead->lng}}},
              
              name : '{{$lead->businessname}}',
              contentString: 
                  '{{$lead->info}}',
            },
      @endforeach
        
        
      };

      function initMap() {
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: {lat: {{$leads->lat}}, lng: {{$leads->lng}}},
          mapTypeId: 'terrain'
        });
      var infowindow = new google.maps.InfoWindow();
        // Construct the circle for each value in citymap.
        // Note: We scale the area of the circle based on the population.
        for (var lead in leadmap) {
          // Add the circle for this city to the map.
          
          var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(leadmap[lead].center),
            title: leadmap[lead].name,
          });
          
          var content = leadmap[lead].contentString;
         
           google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
        return function() {

           infowindow.setContent(content);
           infowindow.open(map,marker);
        };
    })(marker,content,infowindow)); 



        }
      }
        

      google.maps.event.addDomListener(window, 'load', initMap);
    </script>