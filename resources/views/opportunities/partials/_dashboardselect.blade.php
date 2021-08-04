@if($data['me']->user_id != auth()->user()->id)

  @if(isset($data['me']->reports_to) && $data['me']->reports_to != auth()->user()->person->id)
    <p><a href="{{route('manager.dashboard',$data['me']->reports_to)}}">Return to managers dashboard</a></p>
  @endif
  <p><a href="{{route('dashboard.reset')}}">Return to my dashboard</a></p>
@endif