@extends('site/layouts/default')
@section('content')
<h1>Branch Details</h1>
<h2>{{$salesteam->branchname}}</h2>
<p><a href="{{route('branches.index')}}">Return to all branches</a></p>
<div class="row">
<div class="col-sm-3" >
<h4>Branch Number {{$salesteam->id}}</h4>
       
        <h4>Address:</h4>
        <p>{{$salesteam->street}}{{$salesteam->suite}}<br/>
        {{$salesteam->city}},{{$salesteam->state}} {{$salesteam->zip}}<br />
        {{$salesteam->phone}}</p>
       
        <h4>Branch Team</h4>
        @foreach ($salesteam->relatedPeople()->get() as $people)

        <p><strong>{{$roles[$people->pivot->role_id]}}</strong>: 
        <a href = "{{route('salesorg',$people->id)}}" > {{$people->fullName()}}</a> </p>

        @endforeach
        </p>
        <h4>Service Lines:</h4>
        <ul >
            @foreach($salesteam->servicelines as $serviceline)
               <li>  {{$serviceline->ServiceLine}} </li>
            @endforeach
</ul>           


</div>
<div class="col-sm-4" id="map" style="height:300px;width:500px;border:red solid 1px"/>
</div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>

<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$salesteam->lat}},{{$salesteam->lng}});
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
    var name = "{{$salesteam->branchname}}";
    var address = "{{$salesteam->street}}" + "{{$salesteam->address2}}" + "  {{$salesteam->city}}" + " {{$salesteam->state}}" + " {{$salesteam->zip}}";
    var html = "<a href='{{route('branches.show' , $salesteam->id) }}'>" + name + "</a> <br/>" + address;
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

@endsection
