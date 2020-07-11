@extends('site.layouts.default')
@section('content')
<div class="container">
    <h2>{{$person->fullName()}}'s Account {{$type}} Dashboard</h2>
    @include('dashboards.partials._periodselector')
    @include('dashboards.partials._companylist')
    </div>     
</div>
@endsection