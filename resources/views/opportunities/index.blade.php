@extends('site.layouts.default')
@section('content')
<div class="container">

<h2>{{reset($myBranches)}} Branch Opportunities</h2>

<p><a href="">Return To Branch Dashboard</a></p>
@include('dashboards.partials._periodselector')
@php $activityTypes = \App\ActivityType::all(); @endphp
@if(count($myBranches)>1)

<div class="col-sm-4">
    <form name="selectbranch" method="post" action="{{route('opportunity.branch')}}" >
    @csrf

    <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
          @foreach ($myBranches as $key=>$branch)
                <option value="{{$key}}">{{$branch}}</option>
          @endforeach 
    </select>

    </form>
</div>
@endif
    <div class="row">

    @livewire('opportunity-table', ['branch'=>array_keys($myBranches)[0]])

    </div>
</div>
@include('partials._modal')
@include('partials._opportunitymodal')
@include('opportunities.partials._closemodal')
@include('opportunities.partials._activitiesmodal')
@include('partials._scripts')
@endsection