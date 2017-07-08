@extends('site.layouts.maps')
@section('content')
<h2>All Branches</h2>

<p><a href='{{route("branches.index")}}'><i class="glyphicon glyphicon-th-list"></i> List view</a></p>
<?php $route ='branches.statemap';?>
  @include('branches/partials/_state')
  @include('maps.partials._form')  
  @include('partials._branchesmap')
  @include('maps/partials/_keys')

    <div id="map" style="width: 800px; height: 600px"></div>

 

@stop
