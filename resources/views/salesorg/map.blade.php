@extends('site.layouts.maps')
@section('content')
  <h1>{{$data['salesrep']['name']}}</h1>
  <h4>Branches served:</h4>
  @foreach ($data['branch'] as $branch)
    <li><a href="{{route('branch.show',$branch['id'])}}">{{$branch['name']}}</a></li>


  @endforeach
    <div id="map" style="border:solid 1px red"></div>
    <script>
     
      // First, create an object containing LatLng and details for each branch.
      var branchmap = {
        
      @foreach ($data['branch'] as $branch)
            '{{$branch['name']}}' : {
              center : {lat: {{$branch['lat']}}, lng: {{$branch['lng']}}},
              radius : {{$branch['radius']}},
              name : '{{$branch['name']}}',
              contentString: 
                  '{{$branch['info']}}',
            },
      @endforeach
        
        
      };

      function initMap() {
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: {lat: {{$data['salesrep']['lat']}}, lng: {{$data['salesrep']['lng']}}},
          mapTypeId: 'terrain'
        });
      var infowindow = new google.maps.InfoWindow();
        // Construct the circle for each value in citymap.
        // Note: We scale the area of the circle based on the population.
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
            position: new google.maps.LatLng(branchmap[branch].center),
            title: branchmap[branch].name,
          });
          
          var content = branchmap[branch].contentString;
         
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
   
    @endsection
