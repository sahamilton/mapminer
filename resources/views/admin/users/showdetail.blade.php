@extends('admin.layouts.default')
@section('content')
<p><a href="{{route('users.index')}}">Return to all users</a></p>
	<h2>{{$user->person->postName()}}</h2>
    <h4>Reports to:</h4>
    @if(isset($user->person->reportsTo))
        <a href="{{route('users.show',$user->person->reportsTo->user_id)}}">
        {{$user->person->reportsTo->postName()}}</a>
    @endif
    <h4>Serviceline:</h4>
    @foreach ($user->serviceline as $serviceline)
    <li>{{$serviceline->ServiceLine}}</li>
    @endforeach
    <h4>Roles:</h4>
    @foreach ($user->roles as $role)
        <li><a href="{{route('roles.show',$role->id)}}"
        title="See all {{$role->name}} users">{{$role->name}}</a></li>
    @endforeach
    <h4>Branches Serviced:</h4>
    @foreach ($user->person->manages()->get() as $branch)
        <li>{{$branch->branchname}}</li>
    @endforeach
    <h4>Accounts Managed:</h4>
    @foreach ($user->person->managesAccount()->get() as $account)
        <li>{{$account->companyname}}</li>
    @endforeach
@endsection