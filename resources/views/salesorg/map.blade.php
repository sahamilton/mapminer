@extends('site.layouts.maps')
@section('content')
  <h1>{{$salesorg->firstname}} {{$salesorg->lastname}}</h1>
  @if(count($salesorg->reportsTo)==1 && isset($salesorg->reportsTo->id))
  <h4>Reports to:<a href="{{route('salesorg',$salesorg->reportsTo->id)}}" 
  title="See {{$salesorg->reportsTo->firstname}} {{$salesorg->reportsTo->lastname}}'s sales team">
    {{$salesorg->reportsTo->firstname}} {{$salesorg->reportsTo->lastname}}
    </a> 
  @endif
   @if(isset ($salesorg->reportsTo->userdetails) )

    - {{$salesorg->reportsTo->userdetails->roles[0]->name}}
  @endif
</h4>
  <h4>Branches served:</h4>
  @foreach ($salesorg->branchesServiced as $branch)

    <li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a></li>


  @endforeach
    <div id="map" style="border:solid 1px red"></div>
    <script>
     
      // First, create an object containing LatLng and details for each branch.
      var branchmap = {
        
      @foreach ($salesorg->branchesServiced as $branch)
            '{{$branch->branchname}}' : {
              center : {lat: {{$branch->lat}}, lng: {{$branch->lng}}},
              radius : {{$branch->radius}},
              name : '{{$branch->branchname}}',
              contentString: 
                  '{{$branch->info}}',
            },
      @endforeach
        
        
      };

      function initMap() {
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: {lat: {{$salesorg->lat}}, lng: {{$salesorg->lng}}},
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
