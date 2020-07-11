@extends('site.layouts.default')
@section('content')
<div class="container">

<h2>{{$branch->branchname}} Branch Opportunities</h2>

<p><a href="{{route('branchdashboard.show', $branch->id)}}">Return To Branch {{$branch->id}} Dashboard</a></p>

@php $activityTypes = \App\ActivityType::all(); @endphp
@if(count($myBranches)>1)

<div class="col-sm-4">
    <form name="selectbranch" method="post" action="{{route('opportunity.branch')}}" >
    @csrf

    <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
          @foreach ($myBranches as $key=>$branchname)
                <option value="{{$key}}">{{$branchname}}</option>
          @endforeach 
    </select>

    </form>
</div>
@endif
    <div class="row">

    @livewire('opportunity-table', ['branch'=>$branch])

    </div>
</div>
@include('partials._modal')
@include('partials._opportunitymodal')
@include('opportunities.partials._closemodal')
@include('opportunities.partials._activitiesmodal')
@include('partials._scripts')
@endsection