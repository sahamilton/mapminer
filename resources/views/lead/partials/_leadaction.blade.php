@if($location->lead->salesteam->count() >0)
	@include ('lead.partials._closeleadform')
	@if($location->lead->salesteam->first()->pivot->status_id == 1)
		<button type="button" 
			class="btn btn-info " 
			data-toggle="modal" 
			data-target="#claimlead">
			Claim Lead
		</button>
	@elseif($location->lead->salesteam->first()->pivot->status_id == 2)
		<button type="button" 
		class="btn btn-info " 
		data-toggle="modal" 
		data-target="#closelead">
			Close Lead
		</button>
	@else
		<p>
			<strong>Lead Closed: Rated {{$location->lead->salesteam->first()->pivot->rating}}</strong>
		</p>
		<p>
			<a href="{{route('myclosedleads')}}">See all closed leads</a>
		</p>
	@endif
@else
	<button type="button" class="btn btn-info " data-toggle="modal" data-target="#claimlead">Claim Lead</button>
@endif