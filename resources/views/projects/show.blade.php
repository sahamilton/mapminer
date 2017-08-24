@extends('site.layouts.default')
@section('content')

<div class="container">

<h2>Construction Project</h2>
<p><a href="{{route('projects.index')}}">Return to all projects</a> | <a href="{{route('projects.myprojects')}}">Return to my projects</a></p>
<h4><p>{{$project->project_title}}</h4>
<p><strong>Address:</strong>

<blockquote>{{$project->project_addr1}} /{{$project->project_addr2}}<br />{{$project->project_city}}, {{$project->project_state}} 
{{$project->project_zipcode}}
</blockquote>
<div class="row">
<p><strong>People Ready Status:</strong>
@can('manage_projects')
  @include('projects.partials._manageprojects')
@else
@if(count($project->owner)>0)
    {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}</p>
  @else
    Open</p>
@endif
@endcan
</div>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#showmap"><strong>Project Location</strong></a></li>
<li><a data-toggle="tab" href="#details"><strong>Project Details</strong></a></li>
  <li><a data-toggle="tab" href="#contacts"><strong>Project Contacts</strong></a></li>
  <li><a data-toggle="tab" href="#notes"><strong>Project Notes</strong></a></li>
  

</ul>

<div class="tab-content">
  <div id="showmap" class="tab-pane fade in active">
   @include('projects.partials._projectmap')  
  </div>

<div id="details" class="tab-pane fade">
  @include('projects.partials._projectdetails')   
</div>
<div id="contacts" class="tab-pane fade">
  @include('projects.partials._companylist')

</div>

<div id="notes" class="tab-pane fade">
  @include('projects.partials._projectnotes')
</div>
</div>
</div>
@include('partials._modal')
@include('partials/_scripts')


@stop
