	


	<h4>Servicelines:</h4>
    @foreach ($user->serviceline as $serviceline)
    <li>{{$serviceline->ServiceLine}}</li>
    @endforeach
    <h4>Roles:</h4>
    @foreach ($user->roles as $role)
        <li><a href="{{route('roles.show',$role->id)}}"
        title="See all {{$role->name}} users">{{$role->name}}</a></li>
    @endforeach