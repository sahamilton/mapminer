@extends('admin.layouts.default')
@section('content')

<h2>Oracle HR Data</h2>
<p><a href="{{route('oracle.index')}}">Return to Oracle</a></p>
<h3>{{$oracle->fullName()}}</h3>
<p><strong>{{$oracle->job_profile}}</strong></p>
<p>{{$oracle->location_name}}</p>
<p><strong>Team Members</strong></p>
@foreach ($oracle->teamMembers as $team)
   <li>{{$team->fullName()}} <em>{{$team->job_profile}}</em></li>
@endforeach

<p><strong>Oracle Manager</strong></p>
{{$oracle->manager_name}}
<p><strong>Mapminer Manager</strong></p>
{{$oracle->oracleManager ? $oracle->oracleManager->fullName() : ''}}
@endsection
