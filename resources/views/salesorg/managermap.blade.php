@extends('site.layouts.maps')
@section('content')
  <h2>{{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}'s Sales Team</h2>
  @if(isset($salesteam[0]->usersdetails->roles))
  <h3>
  {{$salesteam[0]->usersdetails->roles[0]->name}}
  </h3>
  @endif
  @if(count($salesteam[0]->reportsTo)==1 && isset($salesteam[0]->reportsTo->id))
  <h4>Reports to:<a href="{{route('salesorg',$salesteam[0]->reportsTo->id)}}" 
  title="See {{$salesteam[0]->reportsTo->firstname}} {{$salesteam[0]->reportsTo->lastname}}'s sales team">
    {{$salesteam[0]->reportsTo->firstname}} {{$salesteam[0]->reportsTo->lastname}}
    </a> 
  @endif

  @if(isset ($salesteam[0]->reportsTo->userdetails) )

    - {{$salesteam[0]->reportsTo->userdetails->roles[0]->name}}
  @endif

  </h4>
  @if(isset ($salesteam[0]->userdetails) )
  <p><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:{{$salesteam[0]->userdetails->email}}" title="Email {{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}">{{$salesteam[0]->userdetails->email}}</a> </p>
  @endif
  <p><a href="{{route('salesorg.list',$salesteam[0]->id)}}"
  title="See list view of {{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}'s sales team">
  <i class="glyphicon glyphicon-th-list"></i> List view</a></p>
      <div id="map-container">
        <div style="float:left;width:300px">
  <h2>Direct Reports:</h2>

  @foreach($salesteam[0]->directReports as $reports)
    @if(isset($reports->userdetails))
    @if($reports->isLeaf())
    <a href="{{route('salesorg',$reports->id)}}"
      title="See {{$reports->firstname}} {{$reports->lastname}}'s sales area">
        {{$reports->firstname}} {{$reports->lastname}}</a> 

    @else
      <a href="{{route('salesorg',$reports->id)}}"
      title="See {{$reports->firstname}} {{$reports->lastname}}'s Sales Team">
        {{$reports->firstname}} {{$reports->lastname}}</a>  
    @endif
   
        @if(count($reports->userdetails->roles)>0)
          - {{$reports->userdetails->roles[0]->name}}
        @endif
      <br/>

    @endif
  @endforeach

  </div>

    <div id="map" style="border:solid 1px red"></div> 
  </div>
    <script>
     
      // First, create an object containing LatLng and details for each branch.
      var branchmap = {
        
      @foreach ($salesteam[0]->getLeaves() as $reports)
          
            @foreach ($reports->branchesServiced as $branch)
            
            '{{$branch->branchname}}' : {
              center : {lat: {{$branch->lat}}, lng: {{$branch->lng}}},
              radius : {{$branch->radius}},
              name : '{{$branch->branchname}}',
              contentString: 
                  '<a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a>',
            },
            @endforeach
          
      @endforeach
       
        
      };

      function initMap() {
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: {{! $salesteam[0]->lat ? '4' : '9'}},
          center: {lat: {{! $salesteam[0]->lat ? '39.8282' : $salesteam[0]->lat }}, 
                  lng: {{! $salesteam[0]->lng ? '-98.5795' : $salesteam[0]->lng}} },
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
            title: branchmap[branch].branchname,
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
