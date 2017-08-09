@extends('site.layouts.default')
@section('content')

  <h2>Construction Project</h2>
  <p><a href="{{route('projects.index')}}">Return to all projects</a></p>
  <p><a href="{{route('projects.myprojects')}}">Return to my projects</a></p>
  <h4>{{$project->project_title}}</h4>

  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#project"><strong>Project Details</strong></a></li>

    <li><a data-toggle="tab" href="#notes"><strong>Project Notes</strong></a></li>



  </ul>

  <div class="tab-content">
    <div id="project" class="tab-pane fade in active">
      @include('projects.partials._tabproject')
    </div>
    <div id="notes" class="tab-pane fade in">
      @include('projects.partials._tabnotes')
    </div>
   
  </div>

@include('partials._modal')
@include('partials._noteedit')
@include('projects.partials._map')
@include('partials._scripts')

@stop
