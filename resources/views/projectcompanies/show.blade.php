@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>{{$projectcompany->firm}}</h2>
<h4> Construction Projects</h4>
<p><a href="{{route('projects.index')}}">Return to all projects</a></p>
<p>{{$projectcompany->addr1}}<br />
{{$projectcompany->city}}, {{$projectcompany->state}} {{$projectcompany->zip}}</p>
<ul class="nav nav-tabs">
<<<<<<< HEAD
  <li class="active"><a data-toggle="tab" href="#projects"><strong>Projects</strong></a></li>

  <li><a data-toggle="tab" href="#contacts"><strong>Project Contacts</strong></a></li>
=======
  <li class="nav-item active">
    <a class="nav-link active" data-toggle="tab" href="#projects">
      <strong>Projects</strong>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link"  data-toggle="tab" href="#contacts">
      <strong>Project Contacts</strong>
    </a>
  </li>
>>>>>>> development

</ul>

  <div class="tab-content">
    <div id="projects" class="tab-pane fade in active">
      @include('projectcompanies.partials._companyprojects') 
    </div>
    <div id="contacts" class="tab-pane fade">
      @include('projectcompanies.partials._companycontacts') 
    </div>
   </div>


</div>
@include('partials/_scripts')
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
