@if($data['team']['me']->user_id != auth()->user()->id)

  @if(isset($data['team']['me']->reports_to) && $data['team']['me']->reports_to != auth()->user()->person->id)
    <p><a href="{{route('manager.dashboard',$data['team']['me']->reports_to)}}">Return to managers dashboard</a></p>
  @endif
  <p><a href="{{route('mgrdashboard.reset')}}">Return to my dashboard</a></p>
@endif