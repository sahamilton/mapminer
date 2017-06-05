@extends ('admin.layouts.default')
@section('content')
<h1>Lead</h1>

<h2>{{$lead->businessname}}</h2>
<h4>A location of {{$lead->companyname}}</h4>

 <div id="{{$lead->id}}" data-rating="{{intval(isset($rank) ? $rank : 0)}}" class="starrr" >
            Rated: </div>
<div id="map-container">
	<div style="float:left;width:300px">
		<p><strong>Address:</strong> {{$lead->fullAddress()}}</p>
		<p><strong>Created:</strong> {{$lead->created_at->format('M j, Y')}}</p>
		<p><strong>Available From:</strong> {{$lead->datefrom->format('M j, Y')}}</p>
		<p><strong>Available Until:</strong> {{$lead->dateto->format('M j, Y')}}</p>
		<p><strong>Description:</strong> {{$lead->description}}</p>
		<p><strong>Assigned:</strong>{{count($lead->salesteam)}}</p>
		<p><strong>Lead Source:</strong><a href="{{route('leadsource.show',$lead->leadsource->id)}}">{{$lead->leadsource->source}}</a></p>

		<p><strong>Industry Vertical:</strong></p>
		<ul>
		@foreach($lead->vertical as $vertical)

		<li>{{$vertical->filter}}</li>
		@endforeach
		</ul>
	</div>

@if (count($lead->salesteam) > 0)
<div class="col-md-6 col-md-offset-1">
	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<th>Sales Rep</th>
			<th>Status</th>

		</thead>
		<tbody>
			@foreach ($lead->salesteam as $team)
			<tr>
			<td><a href="{{route('leads.person',$team->id)}}" title="See all leads associated with {{$team->postName()}}">{{$team->postName()}}</a></td>
			<td>{{$sources[$team->pivot->status_id]}}</td>

			</tr>

			@endforeach
		</tbody>
	</table>
</div>
@endif
<h4><strong>Notes</strong></h4>

@foreach ($lead->relatedNotes as $leadnote)

<p>{{$leadnote->created_at->format('M j,Y')}}...<em>{{$leadnote->note}}</em><br />
 --  {{$leadnote->writtenBy->person->postName()}}</p>

@endforeach
</div>
<div id="map" style="height:300px;width:500px;border:red solid 1px"/>
</div>
 <script type="text/javascript" src="{{asset('assets/js/starrr.js')}}"></script>

<script>
$('.starrr').starrr({
	readOnly: true;
});
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>

<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$lead->lat}},{{$lead->lng}});
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var name = "{{$lead->companyname}}";
    var address = "{{$lead->fullAddress()}}";
    var html =  name +  "<br/>" + address;
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