@extends('site.layouts.default')
@section('content')
<div class="container">
    <h2>{{$branch->branchname}} Branch Sales Campaign Planner</h2>
    <h4>{{$campaign->title}}</h4>

    @include('campaigns.partials._branchdetails')

</div>
@include ('partials._scripts')
@endsection