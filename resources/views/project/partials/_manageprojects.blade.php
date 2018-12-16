@if($location->project->owner->count()>0 && $location->project->owner[0]->id != auth()->user()->person()->first()->id)

	{{$location->project->owner[0]->pivot->status}} by {{$location->project->owner[0]->fullName()}}

@elseif($location->project->owner->count()>0 && $location->project->owner[0]->id == auth()->user()->person()->first()->id) 
	@if($location->project->pr_status == 'closed')
		You closed this project on {{$location->project->updated_at->format('M d, Y')}}
		<p><strong>Rating:</strong><div id="rating" data-rating="{{$location->project->owner[0]->pivot->ranking}}" class="starrr" >
		</div></p>
	@else
		You own this project

		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Close Project</button>
		<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#projectTransfer">Transfer Project</button>

		@include ('projects.partials._closeprojectform')
		@include ('projects.partials._transferprojectform')
	@endif 


@else
	<a href="{{route('projects.claim',$location->project->id)}}"><button type="button" class="btn btn-primary">Claim this Project</button></a>
@endif

</p>