@extends('site.layouts.default')
@section('content')
<div class="container">
    <h2>{{$person->fullName()}}'s Account Dashboard</h2>
    <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
        <h4>Top 25 Open Opportunities</h4>
        <canvas id="ctTop25" width="300" height="300"></canvas>
        
    </div>
    <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
        <h4>Win Loss %</h4>
        <canvas id="ctw" width="300" height="300" style="float-right"></canvas>
        
    </div>
    <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
        <h4>Leads</h4>
        <canvas id="ctleads" width="300" height="300"></canvas>
        
    </div>
    <div style="width: 40%;float:left;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
      <h4>Activities</h4>
      <canvas id="ctb" width="300" height="300" style="float-right"></canvas>
        @include('charts._activitiesstackedchart')
    </div>      
</div>
@endsection