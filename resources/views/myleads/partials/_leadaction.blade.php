@if($mylead->salesteam->count() >0)
	@include ('myleads.partials._closeleadform')
	@if($mylead->salesteam->first()->pivot->status_id == 1)
		<button type="button" 
			class="btn btn-info " 
			data-toggle="modal" 
			data-target="#claimlead">
			Claim Lead
		</button>
	@elseif($mylead->salesteam->first()->pivot->status_id == 2)
		<button type="button" 
		class="btn btn-info " 
		data-toggle="modal" 
		data-target="#closelead">
			Close Lead
		</button>
	@else
		<p>
			<strong>Lead Closed: Rated {{$mylead->salesteam->first()->pivot->rating}}</strong>
		</p>
		
	@endif
@else
	<button type="button" class="btn btn-info " data-toggle="modal" data-target="#claimlead">Claim Lead</button>
@endif