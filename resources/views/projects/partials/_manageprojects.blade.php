@if(! $project->owned())
  @if(count($project->owner)>0)
    {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}
  @else
  <a href="{{route('projects.claim',$project->id)}}"><button type="button" class="btn btn-primary">Claim this Project</button></a>
@endif
@else
@if($project->pr_status == 'closed')
You closed this project on {{$project->updated_at->format('M d, Y')}}
<div id="rating" data-rating="{{$project->owner[0]->pivot->ranking}}" class="starrr" >
      </div>
@else
You own this project

 <div class="row"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Close Project</button></div>
      
         @include ('projects.partials._closeprojectform')
@endif
@endif
</p>