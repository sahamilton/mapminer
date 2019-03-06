@if($data['team']['me']->user_id != auth()->user()->id)
  @if($data['team']['me']->reports_to != auth()->user()->person->id)
    <p><a href="{{route('manager.dashboard',$data['team']['me']->reports_to)}}">Return to managers dashboard</a></p>
  @endif
  <p><a href="{{route('dashboard.index')}}">Return to my dashboard</a></p>
@endif