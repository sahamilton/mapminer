<markers>
@foreach ($branches as $branch)

	<marker 
		name = "{{$branch->branchname}}" 
		address="{{trim($branch->street)}} {{trim($branch->address2)}}, {{trim($branch->city)}} {{trim($branch->state)}}" 
		lat="{{$branch->lat}}" 
		lng="{{$branch->lng}}" 
		locationweb="{{route('branches.show',$branch->id)}}" 
		id="{{$branch->id}}" 
		type="branch" 
	@if($branch->servicelines->count()>0)
		brand="{{$branch->servicelines->first()->ServiceLine}}" 
		color="{{$branch->servicelines->first()->color}}"
	@else
		brand="undefined"
		color="blue"
	@endif
	/>
@endforeach
</markers>