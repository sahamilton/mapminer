<h2>Location Details</h2>
<div id="map-container">
	<div style="float:left;width:300px">

		

		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			<p>

			<i class="far fa-user" aria-hidden="true"></i>
			 <b>Primary Contact:</b> {{$location->contacts->count() >0 ? $location->contacts->first()->fullName(): ''}}
			 </p>
			<p>
			<i class="fas fa-map-marker" aria-hidden="true"></i>
			 <b>Address:</b><br/>{{$location->fullAddress()}}</p>
			<p><b><i class="fas fa-phone" aria-hidden="true"></i> Phone:</b>{{$location->phone}}</p>
			
			 <p>Lat: {{number_format($location->lat,4)}};<br /> Lng: {{number_format($location->lng,4)}}</p>
		 </fieldset>

		@if(auth()->user()->hasRole('admin') or $location->user_id == auth()->user()->id)
		<a href="{{route('address.edit',$location->id)}}" 
		title="Edit this location">
		Edit location</a>
		
		@endif
		
	</div>
	 <div id="map" style="height:300px;width:500px;border:red solid 1px">
	</div>
</div>