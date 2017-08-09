@if(! $project->owned())
  @if(count($project->owner)>0)
    {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}</p>
  @else
  <a href="{{route('projects.claim',$project->id)}}">Claim this project </a>
@endif
@else
You own this project</p>
  <form method='post' action="{{route('projects.changestatus')}}">
  <input type="hidden" name="project_id" value="{{$project->id}}" />
  {{csrf_field()}}
      <div class="form-group{{ $errors->has('status)') ? ' has-error' : '' }}">
          <label class="col-md-2 control-label">Change Status</label>
          <div class="col-md-2">
              <select onchange="this.form.submit()" class="form-control" name='status'>
  
              @foreach ($statuses as $status))
                <option 
                @if($project->owner[0]->pivot->status == $status)
                selected
                @endif
                value="{{$status}}">{{$status}}</option>
  
              @endforeach
  
  
              </select>
              <span class="help-block">
                  <strong>{{ $errors->has('status') ? $errors->first('status') : ''}}</strong>
                  </span>
          </div>
      </div>
  </form>
@endif