@extends('admin.layouts.default')
@section('content')
<div class="container" name="selectManagerDashboard" >
    <h2>{{$person->fullName()}}'s Account Dashboard</h2>
    <h4>Branch Activity for {{$company->companyname}}</h4>
    <p><a href="{{route('newdashboard.manager', $person->id)}}">Return to Companies Summary</a></p>
    
    @include('dashboards.partials._periodselector')
    @include('dashboards.partials._branchlist')

</div>
@endsection
