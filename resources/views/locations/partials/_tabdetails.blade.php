<h2>Location Details!!</h2>
<div id="map-container">
	<div style="float:left;width:300px">
		<p><strong>Vertical: </strong>{{isset($location->company->industryVertical->filter) ? $location->company->industryVertical->filter : 'Not Specified'}}</p>
		<p><strong>Segment: </strong>{{isset($location->verticalsegment->filter) ? $location->verticalsegment->filter  : 'Not Specified'}}</p>
		<p><strong>Business Type:</strong> {{isset($location->clienttype->filter) ? $location->clienttype->filter : 'Not Specified'}}
		<p><i>A location of <a href="{{ route('company.show', $location->company->id) }}" title='show all locations of {{$location->company->companyname}} national account'>{{$location->company->companyname}}</a></i><br />
		@if(isset($company->managedBy->firstname))
			Account managed by <a href="{{route('person.show',$location->company->managedBy->id)}}" title="See all accounts managed by {{$location->company->managedBy->postName()}}">
			{{$location->company->managedBy->postName()}}</a>

		</i>
		@endif
		</p>
		<i class="fa fa-search" aria-hidden="true"></i>
		<a href="{{route('salesnotes',$location->company->id)}}" 
		title="Read notes on selling to {{$location->company->companyname}}"> 
		Read 'How to Sell to {{$location->company->companyname}}' </a>
		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			<p>
			<i class="fa fa-user" aria-hidden="true"></i>
			 <b>Primary Contact:</b> {{$location->contact}}
			 </p>
			<p>
			<i class="fa fa-map-marker" aria-hidden="true"></i>
			 <b>Address:</b><br/>{{$location->street}}<br />{{$location->city}}  {{$location->state}} {{$location->zip}}</p>
			<p><b><i class="fa fa-phone" aria-hidden="true"></i> Phone:</b>{{$location->phone}}</p>
			<p><i class="fa fa-address-card-o" aria-hidden="true"></i><a href="{{route('locations.vcard',$location->id)}}">
			 Download vcard </a></p>
			 <p>Lat: {{number_format($location->lat,4)}};<br /> Lng: {{number_format($location->lng,4)}}</p>
		 </fieldset>
		 <p><i class="fa fa-eye" aria-hidden="true"></i>
		@if(isset($watch->location_id))

		<a href="{{route('watch.delete',$watch->id)}}" 
		title="Remove this location to my watch list"> 
		Remove from My Watch List</a>

		@else
			<a href="{{route('watch.add',$location->id)}}" 
			title="Add this location to my watch list"> 
			Add to My Watch List</a>
		@endif
		</p>
		<p>
		
		@isset($branch[0])
			<i class="fa fa-location-arrow" aria-hidden="true"></i>
			<b>Closest Branch: </b>
			<a href="{{ route('branches.show', $branch[0]->id) }}" 
			title='show all {{trim($branch[0]->branchname)}} national accounts'>
			{{$branch[0]->id}}:{{$branch[0]->branchname}} </a>
		 
		@else
			<p>Closest Branch: <a href="{{ route('assign.location', $location->id) }}" 
			title='Find the closest branch'>Closest Branch</a>
		@endif
		</p>
		<p> <i class="fa fa-map-signs" aria-hidden="true"></i> 
		<a href="{{ route('assign.location', $location->id) }}" 
		title='See nearby branches'>Other Nearby Branches</a></p>
		<a href="{{route('locations.edit',$location->id)}}" 
		title="Edit this location">
		<i class="fa fa-pencil text-info" aria-hidden="true"></i>Edit location</a>
		<hr />
	</div>
	 <div id="map" style="height:300px;width:500px;border:red solid 1px">
	</div>
</div>