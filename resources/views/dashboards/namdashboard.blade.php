@extends('site.layouts.default')
@section('content')
<div class="container">
    <h2>{{$person->fullName()}}'s Account Dashboard</h2>
    @include('dashboards.partials._periodselector')
    @include('dashboards.partials._namsummary')
    <div class="row">
        <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
            <h4><a href="">Open Opportunities</a></h4>
            <canvas id="ctopportunities" width="300" height="300"></canvas>
            @include('charts._openopportunitytypechart')
        </div>
        <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
            <h4>Win Loss %</h4>
            <canvas id="ctw" width="300" height="300" style="float-right"></canvas>
            @include('charts._winlosschart')
        </div>
    </div>
    <div class="row">
        <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
            <h4><a href="{{route('newdashboard.leads', $person->id)}}">Leads</a></h4>
            <canvas id="ctleads" width="300" height="300"></canvas>
            @include('charts._leadsstackedchart')
        </div>
        <div style="width: 40%;float:left;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
          <h4><a href="">Activities</a></h4>
          <canvas id="ctb" width="300" height="300" style="float-right"></canvas>
            @include('charts._activitiesstackedchart')
        </div> 
    </div>     
</div>
@endsection