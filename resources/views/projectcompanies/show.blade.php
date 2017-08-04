@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>{{$projectcompany->firm}} Construction Project</h2>
<p><a href="{{route('projects.index')}}">Return to all projects</a></p>
@include('projectcompanies.projectlist')
</div>
@include('partials/_scripts')
@stop
