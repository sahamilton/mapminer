@extends('site.layouts.default')
@section('content')

<h2>{{$leads->first()->branch->branchname}} Leads!!</h2>
<p><a href="{{route('dashboard.show',session('branch'))}}">Return To Branch Dashboard</a></p>
<div class="row float-right"><button type="button" 
    class="btn btn-info float-right" 
    data-toggle="modal" 
    data-target="#add_lead">
      Add Lead
</button>
</div>
@php $route = "branch.leads";
$branch = $leads->first()->branch;@endphp

@include('branches.partials._selector')
@include('branches.partials._tableads')

@include('partials._scripts')
@endsection
