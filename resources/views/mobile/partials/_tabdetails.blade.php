<h2>Location Details</h2>
<div id="map-container" style="margin-bottom:80px">
	<div style="float:left;width:300px">

		

		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			<p>

			<i class="far fa-user" aria-hidden="true"></i>
			 <b>Primary Contact:</b> {{$location->contacts->count() >0 ? $location->contacts->first()->completeName : ''}}
			 </p>
			<p>
			<i class="fas fa-map-marker" aria-hidden="true"></i>
			 <b>Address:</b><br/>{{$location->fullAddress()}}</p>
			<p><b><i class="fas fa-phone" aria-hidden="true"></i> Phone:</b>
				<a href="tel:{{$location->phone}}">{{$location->phoneNumber}}</a>
			</p>
			
			 <p>Lat: {{number_format($location->lat,4)}};<br /> Lng: {{number_format($location->lng,4)}}</p>
		 </fieldset>

		@if(auth()->user()->hasRole('admin') or $location->user_id == auth()->user()->id)
			<a class="text text-info" href="{{route('address.edit',$location->id)}}" 
			title="Edit this location">
			<i class="far fa-edit"></i>
			Edit Location</a>
			@if($location->activities->count()==0)
				<a data-href="{{route('address.destroy',$location->id)}}" 
					data-toggle="modal" 
					data-target="#confirm-delete" 
					data-title = "This address and all its associations" href="#">
					<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
				Delete Locaton</a>
			
			@endif
		@endif
		
	</div>
	 <div id="map" style="height:300px;width:300px;border:red solid 1px">
	</div>
</div>