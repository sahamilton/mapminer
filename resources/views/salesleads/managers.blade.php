@extends('site.layouts.default')
@section('content')
<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#map"><strong>Team Prospects</strong></a></li>

	<li><a data-toggle="tab" href="#list"><strong>My Prospects</strong></a></li>

	

</ul>

<div class="tab-content">
	<div id="map" class="tab-pane fade in active">
		@include('salesleads.partials._managerleads')
	</div>
	<div id="list" class="tab-pane fade in">

	</div>

</div>







@include('partials/_scripts')



@stop