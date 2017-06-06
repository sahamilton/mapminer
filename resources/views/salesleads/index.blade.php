@extends('site.layouts.maps')
@section('content')

<h1>{{$title }} {{$leads->firstname}} {{$leads->lastname}}</h1>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home"><strong>Offered Leads</strong></a></li>
  <li><a data-toggle="tab" href="#menu1"><strong>Owned Leads</strong></a></li>
  <li><a data-toggle="tab" href="#map"><strong>Map View</strong></a></li>
  

</ul>
<div class="tab-content">
<div id="home" class="tab-pane fade in active">

@include('salesleads.partials._offeredleads')
</div>
<div id="menu1" class="tab-pane fade">
@include('salesleads.partials._ownedleads')

</div>

<div id="map" class="tab-pane fade">
@include('salesleads.partials._tabmapleads')

</div>
</div>




@include('partials/_scripts')



@stop