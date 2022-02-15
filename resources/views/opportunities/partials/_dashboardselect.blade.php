
@if($data['manager']->user_id != auth()->user()->id)

  @if(isset($data['manager']->reportsTo) 
    && $data['manager']->reports_to != auth()->user()->person->id)
    <p><a href="{{route('manager.dashboard',$data['manager']->reports_to)}}">Return to {{$data['manager']->reportsTo->fullName()}}'s dashboard</a></p>
  @endif
  <p><a href="{{route('dashboard.reset')}}">Return to my dashboard</a></p>
@endif