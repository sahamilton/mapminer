@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>{{$projectcompany->firm}}</h2>
<p>{{$projectcompany->addr1}}<br />
{{$projectcompany->city}}, {{$projectcompany->state}} {{$projectcompany->zipcode}}</p>

@foreach ($projectcompany->employee as $employee)
<p>{{$employee->contact}}<br /> {{$employee->title}}<br />  {{$employee->phone}} </p>
@endforeach
<h2> Construction Projects</h2>
<p><a href="{{route('projects.index')}}">Return to all projects</a></p>
@include('projectcompanies.projectlist')
</div>
@include('partials/_scripts')
@stop
