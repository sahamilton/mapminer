@extends('site.layouts.default')
@section('content')

<h2>{{$title }} {{$leads->firstname}} {{$leads->lastname}}</h2>
@if($manager)
	<p><a href="{{route('salesleads.index')}}">Return to sales team</a></p>
@endif
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#map"><strong>Map View</strong></a></li>

  <li><a data-toggle="tab" href="#offered"><strong>Offered Leads</strong></a></li>
  <li><a data-toggle="tab" href="#owned"><strong>Owned Leads</strong></a></li>
  

</ul>

<div class="tab-content">
<div id="map" class="tab-pane fade in active">
	@include('salesleads.partials._tabmapleads')

</div>
<div id="offered" class="tab-pane fade">
	@include('salesleads.partials._offeredleads')

</div>

<div id="owned" class="tab-pane fade">
	@include('salesleads.partials._ownedleads')
</div>
</div>
    @include('salesleads.partials._maps')




@include('partials/_scripts')



@stop