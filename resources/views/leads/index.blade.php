@extends('admin.layouts.default')
@section('content')

<h1>Propsects</h1>




<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#map"><strong>Map View</strong></a></li>

	<li><a data-toggle="tab" href="#list"><strong>List</strong></a></li>

	<li><a data-toggle="tab" href="#team"><strong>Sales Team</strong></a></li>

</ul>

<div class="tab-content">
	<div id="map" class="tab-pane fade in active">
		@include('leads.partials._tabmapleads')
	</div>
	<div id="list" class="tab-pane fade in">
		@include('leads.partials._tablist')
	</div>
	<div id="team" class="tab-pane fade in">
		@include('leads.partials._tabteam')
	</div>
</div>
   
@include('partials._scripts')
@stop