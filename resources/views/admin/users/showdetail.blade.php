@extends('admin.layouts.default')
@section('content')
<div class="container">
    <p><a href="{{route('users.index')}}">Return to all users</a></p>
    <h2>{{$user->person->postName()}}</h2>
    
    <p><strong>Email:</strong> {{$user->email}}</p>
    <p><strong>Roles:</strong>
        @foreach($user->roles as $role)
            <li>{{$role->name}}</li>
        @endforeach
    </p>
    <p><strong>Last Login:</strong>

   {{$user->lastlogin  ? $user->lastlogin->format('d/m/Y'): 'Never Logged in'}}
    </p>
    <ul class="nav nav-tabs">
    <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#showmap"><strong>Location</strong></a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#details"><strong>Details</strong></a></li>
    
    @if($user->person->directReports()->get()->count()>0 or $user->person->reportsTo()->get()->count()>0)
    <li class="nav-item"><a class="nav-link"  data-toggle="tab" href="#team"><strong>Reporting Structure</strong></a></li>
    @endif
    @if($user->person->manages()->get()->count()>0)
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#branches"><strong>Branches Serviced</strong></a></li>
    @endif
    @if($user->person->managesAccount()->get()->count()>0)
    <li class="nav-item"><a class="nav-link"  data-toggle="tab" href="#accounts"><strong>Accounts Managed</strong></a></li>
    @endif
        @if($user->person->templeads->count()>0)
    <li class="nav-item"><a class="nav-link"  data-toggle="tab" href="#leads"><strong>Assigned Leads ({{$user->person->templeads->count())}})</strong></a></li>
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
         <div id="leads" class="tab-pane fade in">
            @php $openleads = $user->person->openleads @endphp
            @include('templeads.partials._tabopenleads')
        </div>

    </div>
</div>
@include('partials._scripts')
@endsection