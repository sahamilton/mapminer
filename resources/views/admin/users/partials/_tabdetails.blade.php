	<p><strong>User id:</strong>{{$user->id}}</p>
	<p><strong>Person id:</strong>{{$user->person->id}}</p>
	<p><strong>Employee id:</strong>{{$user->employee_id}}</p>


	<h4>Servicelines:</h4>
    @foreach ($user->serviceline as $serviceline)
    <li>{{$serviceline->ServiceLine}}</li>
    @endforeach
    <h4>Roles:</h4>
    @foreach ($user->roles as $role)
        <li><a href="{{route('roles.show',$role->id)}}"
        title="See all {{$role->displayName}} users">{{$role->displayName}}</a></li>
    @endforeach