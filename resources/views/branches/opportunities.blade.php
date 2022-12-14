@extends('site.layouts.default')
@section('content')
<h2>{{$branch->branchname}} Opportunities!!</h2>
<p><a href="{{route('branchdashboard.show', session('branch'))}}">Return To Branch Dashboard</a></p>
@include('dashboards.partials._periodselector')
<div class="row float-right"><button type="button" 
    class="btn btn-info float-right" 
    data-toggle="modal" 
    data-target="#add_lead">
      Add Lead
</button>
</div>
@php $route= "branch.opportunities"; @endphp
@include('branches.partials._selector')
@include('branches.partials._tabopportunities')
@include('partials._scripts')
@endsection
