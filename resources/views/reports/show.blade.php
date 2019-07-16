@extends('admin.layouts.default')
@section('content')
<h2>{{$report->report}}</h2>
<p><a href="{{route('reports.index')}}">Back to all reports</a></p>
<p>{{$report->description}}</p>
<p><label><strong>Model:</strong></label>{{ucwords($report->type)}}</p>
@if($report->distribution->count() >0)
    @include('reports.partials._distribution')
@endif

@if($report->roledistribution->count() >0)
    @include('reports.partials._roleDistribution')
@endif

@if($report->companyDistribution->count() >0)
    @include('reports.partials._companydistribution')
@endif

@include('partials._scripts')
@endsection