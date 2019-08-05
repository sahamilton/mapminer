<h2>Location Details</h2>
<div id="map-container">
	<div style="float:left;width:300px">

		

		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			<p>

			<i class="far fa-user" aria-hidden="true"></i>
			 <b>Primary Contact:</b> {{$address->contacts->count() >0 ? $address->contacts->first()->fullName(): ''}}
			 </p>
			<p>
			<i class="fas fa-map-marker" aria-hidden="true"></i>
			 <b>Address:</b><br/>{{$address->fullAddress()}}</p>
			<p><b><i class="fas fa-phone" aria-hidden="true"></i> Phone:</b>{{$address->phone}}</p>
			
			 <p>Lat: {{number_format($address->lat,4)}};<br /> Lng: {{number_format($address->lng,4)}}</p>
		 </fieldset>

		@if(auth()->user()->hasRole('admin') or $address->user_id == auth()->user()->id)
		<a class="text text-info" href="{{route('address.edit',$address->id)}}" 
		title="Edit this location">
		<i class="far fa-edit"></i>
		Edit Location</a>
		@if($address->activities->count()==0)
		<a data-href="{{route('address.destroy',$address->id)}}" 
			data-toggle="modal" 
			data-target="#confirm-delete" 
			data-title = "This address and all its associations" href="#">
			<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
		Delete Locaton</a>
	
		@endif
		@endif
		
	</div>
	 <div id="map" style="height:300px;width:500px;border:red solid 1px">
	</div>
</div>