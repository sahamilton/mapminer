<h2>Location Details</h2>
<div id="map-container">
	<div style="float:left;width:300px">
		<p>
		<p><i>A location of {{$location->lead->companyname}}</a></i></p>

		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			<p>

			<i class="far fa-user" aria-hidden="true"></i>
			 <b>Primary Contact:</b> {{$location->lead->contact}}
			 </p>
			<p>
			<i class="fas fa-map-marker" aria-hidden="true"></i>
			 <b>Address:</b><br/>{{$location->lead->address}}</p>
			<p><b><i class="fas fa-phone" aria-hidden="true"></i> Phone:</b>{{$location->lead->phone}}</p>
			
			 <p>Lat: {{number_format($location->lead->lat,4)}};<br /> Lng: {{number_format($location->lead->lng,4)}}</p>
		 </fieldset>

		
		<a href="{{route('myleads.edit',$location->lead->id)}}" 
		title="Edit this lead">
		Edit lead</a>
	</div>
	 <div id="map" style="height:300px;width:500px;border:red solid 1px">
	</div>
</div>