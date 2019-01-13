<h2>Location Watched By:</h2>
@foreach ($location->watchedBy as $watcher)

<li>{{$watcher->person->fullName()}} {{$watcher->pivot->created_at->format('jS F Y')}}</li>
@endforeach


