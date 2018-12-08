@if($project->owner->count()>0 && $project->owner[0]->id != auth()->user()->person()->first()->id)

	{{$project->owner[0]->pivot->status}} by {{$project->owner[0]->fullName()}}

@elseif($project->owner->count()>0 && $project->owner[0]->id == auth()->user()->person()->first()->id) 
	@if($project->pr_status == 'closed')
		You closed this project on {{$project->updated_at->format('M d, Y')}}
		<p><strong>Rating:</strong><div id="rating" data-rating="{{$project->owner[0]->pivot->ranking}}" class="starrr" >
		</div></p>
	@else
		You own this project

		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Close Project</button>
		<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#projectTransfer">Transfer Project</button>

		@include ('projects.partials._closeprojectform')
		@include ('projects.partials._transferprojectform')
	@endif 


@else
	<a href="{{route('projects.claim',$project->id)}}"><button type="button" class="btn btn-primary">Claim this Project</button></a>
@endif

</p>