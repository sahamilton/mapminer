<h2>Location Details</h2>
@if($location->duplicates->count() > 1 && $owned)
	<div class="alert alert-danger">
		<p><strong>Possible Duplicate(s)</strong> {{$location->duplicates->count()}}- 
			<a href="{{route('address.duplicates', $location->id)}}"><button class="btn btn-danger">Merge?</button></a></p>
	</div>
@endif
<div id="map-container">
	<div style="float:left;width:300px">



		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			<p>
			<i class="far fa-user" aria-hidden="true"></i>
			 <b>Primary Contact:</b> <span id="primaryContact">
			 	{{$location->primaryContact->count() ? $location->primaryContact->first()->fullName() : ''}}
			 </span>
			 </p>
			<p>
				<i class="fas fa-map-marker" aria-hidden="true"></i>
			 	<b>Address:</b>
			 	<br/>{{$location->fullAddress()}}
			 </p>
			<p>
				<b>
					<i class="fas fa-phone" aria-hidden="true"></i> 
					Phone:
				</b>{{$location->phone }
			</p>
			
			 <p>Lat: {{number_format($location->lat,4)}};<br /> Lng: {{number_format($location->lng,4)}}</p>
		 </fieldset>

		@if($owned)
			<a class="text text-info" href="{{route('address.edit',$location->id)}}" 
			title="Edit this location">
			<i class="far fa-edit"></i>
			Edit Location</a>
			
			<a data-href="{{route('branchleads.destroy',$location->assignedToBranch->where('id', $branch->id)->first()->pivot->id)}}" 
				data-toggle="modal" 
				data-target="#delete-lead" 
				data-title = "this address and all its related branch activities and opportunities" href="#">
				<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
			Delete Locaton</a>
	
		
		@elseif ($location->createdBy)

			<p>Lead Created by: <a href="{{route('user.show',$location->createdBy->id)}}">{{$location->createdBy->postName()}}</a></p>

		@endif
		
	</div>
	 <div id="map" style="height:300px;width:500px;border:red solid 1px">
	</div>
</div>