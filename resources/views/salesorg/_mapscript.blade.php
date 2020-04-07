
    <script>
     
      // First, create an object containing LatLng and details for each branch.
      

      var branchmap = {
        
      @foreach ($salesteam->getDescendantsAndSelf() as $reports)
         

            @foreach ($reports->branchesServiced as $branch)
            
            '{{$branch->branchname}}' : {
              type: 'branch',
              center : {lat: {{$branch->lat}}, lng: {{$branch->lng}}},
              radius : {{$branch->radius}},
              name : '{{$branch->branchname}}',
              contentString: 
                  '<a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a>',
            },
            @endforeach

        

            '{{$reports->fullName()}}' : {
              type : 'person',
              center : {lat: {{isset($reports->lat) ? $reports->lat:0}}, lng: {{isset($reports->lng) ? $reports->lng:0}}},
              radius :25,
              name : '{{$reports->fullName()}}',
              contentString:'<a href="{{route('salesorg.show',$reports->id)}}">{{$reports->fullName()}}</a>',
            },
         
          
      @endforeach
       
        
      };

      function initMap() {
        var Geo={

        };
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: {{! $salesteam->lat ? '4' : '9'}},
          center: {lat: {{! $salesteam->lat ? '39.8282' : $salesteam->lat }}, 
                  lng: {{! $salesteam->lng ? '-98.5795' : $salesteam->lng}} },
         
          mapTypeId: 'terrain'
        });
      var infowindow = new google.maps.InfoWindow();
        // Construct the circle for each value in map.
        // Note: We scale the area of the circle based on the service radius
        for (var branch in branchmap) {
          // Add the circle for this city to the map.
          var branchCircle = new google.maps.Circle({
              strokeColor: '#FF0000',
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: '#00FF00',
              fillOpacity: 0.35,
              map: map,

              center: branchmap[branch].center,
              radius: branchmap[branch].radius * 1600,
            });
          var marker = new google.maps.Marker({
            map: map,
            icon: icon,
            position: new google.maps.LatLng(branchmap[branch].center),
            title: branchmap[branch].branchname,
          });

          var icon = getMarker(branchmap[branch].type);
            function getMarker(type){
            if(type == 'branch'){
                  return '//maps.google.com/mapfiles/ms/icons/blue-dot.png';
                }else{
                  return '//maps.google.com/mapfiles/ms/icons/red-dot.png';
                 };

          }
          var content = branchmap[branch].contentString;
         
           google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
        return function() {

           infowindow.setContent(content);
           infowindow.open(map,marker);
        };
    })(marker,content,infowindow)); 



        }
      }
        navigator.geolocation.getCurrentPosition(function(position) {
        var geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        var address = getAddress(geolocate);
        Geo.lat = position.coords.latitude;
        Geo.lng = position.coords.longitude;
        populateHeader(Geo.lat, Geo.lng,address,'');
        var marker = new google.maps.Marker({
          position: geolocate,
          map: map,
          icon: '//maps.google.com/mapfiles/ms/icons/yellow-dot.png',
          title: 'You are here!'
        });
        
        
   function getAddress(latLng) {
      
      var geocoder = new google.maps.Geocoder();
      if (geocoder) {
        geocoder.geocode({ 'latLng': latLng}, function (results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
          var address = results[0].formatted_address; 
          $('#address').val(address);
           }
           else {
          $('#address').val(latLng);;
          
           }
        })
          } 
      return address;
    }
  function populateHeader(lat, lng, address, distance){
    $('#lat:first').val(lat);
    $('#lng:first').val(lng);
    $("#address").val(address);
    
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


      google.maps.event.addDomListener(window, 'load', initMap);
    </script>