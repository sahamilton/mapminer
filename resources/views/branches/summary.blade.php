@extends('site.layouts.default')
@section('content')

<h2>{{$upcoming->branchname}} Week Planner</h2>
@if($upcoming->opportunitiesClosingThisWeek->count()>0)
    <h4>Opportunities Scheduled to Close this Week</h4>
    @php 
      $data['opportunities'] = $upcoming->opportunitiesClosingThisWeek;
    @endphp
      @include('opportunities.partials._tabopenopportunities')
@endif
@if($upcoming->pastDueOpportunities->count()>0)
<h4>Open Opportunities Scheduled to Close Prior to Today</h4>
    @php 
    $data['opportunities'] = $upcoming->pastDueOpportunities;
    @endphp
    @include('opportunities.partials._tabopenopportunities')
@endif
@if($activities && $activities->count() > 0)
<h4>Open Activities to be completed before end of this week</h4>
@php
    $data['activities'] = $activities;
    @endphp
    @include('activities.partials._upcoming')
@endif
@include('partials._scripts')




@endsection