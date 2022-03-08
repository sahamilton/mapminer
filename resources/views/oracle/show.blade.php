@extends('admin.layouts.default')
@section('content')

<h2>Oracle HR Data</h2>
<p><a href="{{route('oracle.index')}}">Return to Oracle</a></p>
<h3>{{$oracle->fullName()}}</h3>
<p><strong>{{$oracle->job_profile}}</strong></p>
@if($oracle->mapminerUser)
   <p><a href="{{route('person.details', $oracle->mapminerUser->person->id)}}">See Profile</a>
   <i class="far fa-check-circle text-success" title="Mapminer user"></i></p>
@else
   Not In Mapminer
   <p><a href="{{route('oracle.useradd', $oracle->id)}}" class="btn btn-success">Add to Mapminer</a></p>
@endif

<p>{{$oracle->location_name}}</p>
<p><strong>Team Members</strong></p>
@foreach ($oracle->teamMembers as $team)

   <li>
      @if($team->mapminerUser)
      <i class="far fa-check-circle text-success" title="Mapminer user"></i>
         <a href="{{route('person.details', $team->mapminerUser->person->id)}}">
            {{$team->fullName()}}
         </a>
      
      
         
      @else
         <i class="far fa-times-circle text-danger" title="Not a Mapminer user"></i>
          <a href="{{route('oracle.show', $team->id)}}">{{$team->fullName()}}</a>
          
      @endif
      {{$team->job_profile}} {{$team->location_name}}
   </li>
@endforeach

<p><strong>Manager</strong></p>

@if($oracle->oracleManager)
@if(isset($oracle->oracleManager->mapminerUser))
<a href="{{route('user.show', $oracle->oracleManager->mapminerUser->id)}}">
{{$oracle->oracleManager->mapminerUser->person->fullName()}}
</a>
 <i class="far fa-check-circle text-success" title="Mapminer user"></i>
@else
{{$oracle->oracleManager->fullName()}}
@endif
@endif
@endsection
