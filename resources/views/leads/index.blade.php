@extends('admin/layouts/default')
@section('content')

<h1>Leads</h1>


@if (Auth::user()->hasRole('Admin'))

<div class="pull-right">
				<a href="{{ route('leads.create')}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Lead</a>
                <a href="{{ route('batchimport')}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-open-file"></span> Import Leads</a>
			</div>
@endif

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
   
@include('partials/_scripts')
@stop