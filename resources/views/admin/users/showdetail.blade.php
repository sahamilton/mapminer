@extends('admin.layouts.default')
@section('content')
<div class="container">
    <p><a href="{{route('users.index')}}">Return to all users</a></p>
    <h2>{{$user->person->postName()}}</h2>
    <p><strong>User Name:</strong> {{$user->username}}</p>
    <p><strong>Email:</strong> {{$user->email}}</p>
    <p><strong>Roles:</strong>
        @foreach($user->roles as $role)
            <li>{{$role->name}}</li>
        @endforeach
    </p>
    <p><strong>Last Login:</strong>
    @if($user->lastlogin)
    {{$user->lastlogin->format('d/m/Y')}}
    @else
    Never Logged in
    @endif
    </p>
    <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#showmap"><strong>Location</strong></a></li>
    <li><a data-toggle="tab" href="#details"><strong>Details</strong></a></li>
    
    @if(count($user->person->directReports()->get())>0 or count($user->person->reportsTo()->get())>0)
    <li><a data-toggle="tab" href="#team"><strong>Reporting Structure</strong></a></li>
    @endif
    @if(count($user->person->manages()->get())>0)
    <li><a data-toggle="tab" href="#branches"><strong>Branches Serviced</strong></a></li>
    @endif
    @if(count($user->person->managesAccount()->get())>0)
    <li><a data-toggle="tab" href="#accounts"><strong>Accounts Managed</strong></a></li>
    @endif

    </ul>
    <div class="tab-content">
        <div id="showmap" class="tab-pane fade in active">
            @include('admin.users.partials._personmap')
        </div>
        
        <div id="details" class="tab-pane fade in">
            @include('admin.users.partials._tabdetails')
        </div>

        <div id="team" class="tab-pane fade in">
            <h4>Reports to:</h4>
            @if(isset($user->person->reportsTo))
            <a href="{{route('users.show',$user->person->reportsTo->user_id)}}">
            {{$user->person->reportsTo->postName()}}</a>
            @endif
            <hr />
            <h4>Team:</h4>
            @foreach ($user->person->directReports()->get() as $team)
                <li>
                <a href="{{route('users.show',$team->user_id)}}">{{$team->firstname}} {{$team->lastname}}
                </a>
                </li>
            @endforeach
        </div>
        
        <div id="branches" class="tab-pane fade in">
            <h4>Branches Serviced:</h4>
            @foreach ($user->person->manages()->get() as $branch)

                <li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a> {{$roles[$branch->pivot->role_id]}}</li>
            @endforeach
        </div>

        <div id="accounts" class="tab-pane fade in">

            <h4>Accounts Managed:</h4>
            @foreach ($user->person->managesAccount()->get() as $account)
                <li>{{$account->companyname}}</li>
            @endforeach
        </div>

    </div>
</div>
@endsection