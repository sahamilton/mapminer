@extends('admin.layouts.default')
@section('content')

<h2>Oracle HR Data</h2>
<p><a href="{{route('oracle.index')}}">Return to Oracle</a></p>
<h3>{{$oracle->fullName()}}</h3>
<p><strong>{{$oracle->job_profile}}</strong></p>
@if($oracle->mapminerUser)
   <p><a href="{{route('person.details', $oracle->mapminerUser->person->id)}}">See Profile</a></p>
@else
   Not In Mapminer
@endif

<p>{{$oracle->location_name}}</p>
<p><strong>Team Members</strong></p>
@foreach ($oracle->teamMembers as $team)

   <li>
      @if($team->mapminerUser)
         <a href="{{route('person.details', $team->mapminerUser->person->id)}}">
            {{$team->fullName()}}
         </a>
      
      
         <i class="far fa-check-circle text-success" title="Mapminer user"></i>
      @else
          {{$team->fullName()}}
      @endif
      {{$team->job_profile}} {{$team->location_name}}
   </li>
@endforeach

<p><strong>Manager</strong></p>
@if($oracle->mapminerManager)
   @if($oracle->manager_name == $oracle->mapminerManager->postName())
      <a href="{{route('person.details',$oracle->mapminerManager->person->id)}}">
         
         {{$oracle->mapminerManager->person->fullName()}}
      </a>
   
   @else
      <p>Reassign {{$oracle->fullName()}}</p>
   @endif

@else
   {{dd($oracle)}}{{$oracle->manager_name}}
@endif


@endsection
