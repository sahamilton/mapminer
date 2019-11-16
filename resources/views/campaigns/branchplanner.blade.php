@extends('site.layouts.default')
@section('content')
<div class="container">
    <h2>{{$branch->branchname}} Branch Sales Initiatives from Campaign</h2>
    <h4>{{$campaign->title}}</h4>
    @include('campaigns.partials._campaignselector')
    @include('campaigns.partials._branchdetails')

</div>
@include ('partials._scripts')
@endsection