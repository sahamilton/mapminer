@extends('site.layouts.maps')
@section('content')

  <h2>{{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}'s Sales Team</h2>
  @foreach ($salesteam[0]->userdetails->roles as $role)
    {{$role->name}}
  @endforeach
  @if(isset($salesteam[0]->usersdetails->roles))
  <h3>
  @foreach ( $salesteam[0]->usersdetails->roles as $role)
  {{$role->name}}
  @endforeach
  </h3>
  @endif

  @if($salesteam[0]->reportsTo)
  <h4>Reports to:<a href="{{route('salesorg',$salesteam[0]->reportsTo->id)}}" 
  title="See {{$salesteam[0]->reportsTo->firstname}} {{$salesteam[0]->reportsTo->lastname}}'s sales team">
    {{$salesteam[0]->reportsTo->firstname}} {{$salesteam[0]->reportsTo->lastname}}
    </a> 
  @endif

@if(isset ($salesteam[0]->reportsTo->userdetails->roles) && $salesteam[0]->reportsTo->userdetails->roles->count()>0) 
    - {{$salesteam[0]->reportsTo->userdetails->roles[0]->name}}
  @endif

  </h4>
  @if(isset ($salesteam[0]->userdetails) && $salesteam[0]->userdetails->email != '')

  <p><i class="far fa-envelope" aria-hidden="true"></i> <a href="mailto:{{$salesteam[0]->userdetails->email}}" title="Email {{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}">{{$salesteam[0]->userdetails->email}}</a> </p>
  @endif
  <p><a href="{{route('salesorg.list',$salesteam[0]->id)}}"
  title="See list view of {{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}'s sales team">
  <i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>

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
     
      @if($reports->userdetails->roles->count()>0)
        - {{$reports->userdetails->roles[0]->name}}
      @endif
      <br/>

    @endif
  @endforeach

  </div>
  <div class="container" style="float:right;width:700px;">
    @php  $data['type'] ='people'; @endphp
  @include('leads.partials.search')
<p>Branches = <img src='//maps.google.com/mapfiles/ms/icons/blue-dot.png' />
Sales Team  = <img src='//maps.google.com/mapfiles/ms/icons/red-dot.png' /></p>
    <div id="map" style="border:solid 1px red;margin-bottom:40px;"></div> 
  </div> </div>
    <script>
     
      // First, create an object containing LatLng and details for each branch.
      

      var branchmap = {
        
      @foreach ($salesteam[0]->getDescendantsAndSelf() as $reports)
         

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

        

            '{{$reports->firstname}} {{$reports->lastname}}' : {
              type : 'person',
              center : {lat: {{isset($reports->lat) ? $reports->lat:0}}, lng: {{isset($reports->lng) ? $reports->lng:0}}},
              radius :25,
              name : '{{$reports->firstname}} {{$reports->lastname}}',
              contentString:'<a href="{{route('salesorg',$reports->id)}}">{{$reports->firstname}} {{$reports->lastname}}</a>',
            },
         
          
      @endforeach
       
        
      };

      function initMap() {
        var Geo={

        };
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: {{! $salesteam[0]->lat ? '4' : '9'}},
          center: {lat: {{! $salesteam[0]->lat ? '39.8282' : $salesteam[0]->lat }}, 
                  lng: {{! $salesteam[0]->lng ? '-98.5795' : $salesteam[0]->lng}} },
         
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
   
    @endsection
