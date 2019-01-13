@extends ('admin.layouts.default')
@section('content')
<h2>Prospect Source - {{$leadsource->source}}</h2>

<p><a href="{{route('leadsource.export',$leadsource->id)}}"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i>  Export owned and closed prospects</a></p>
<p><a href="{{route('leadsource.index')}}">Return to all Prospect sources</a></p>
<ul class="nav nav-tabs">
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" href="#map">
			<strong>Map View</strong>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#details">
			<strong>Details</strong>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link"  data-toggle="tab" href="#team">
			<strong>Team</strong>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link"  data-toggle="tab" href="#unassigned">
			<strong>Unassigned Prospects</strong>
		</a>
	</li>
	
	
	
</ul>
<?php $unassigned = array();?>
<div class="tab-content">
	<div id="map" class="tab-pane fade show active">
	@include('leadsource.partials._tabmap')
	</div>
	<div id="details" class="tab-pane fade ">
	@include('leadsource.partials._tabdetails')
	</div>
	
	<div id="team" class="tab-pane fade ">
	@include('leadsource.partials._tabteam')
	</div>
	<div id="unassigned" class="tab-pane fade ">

	@include('leadsource.partials._tabunassignedleads')
	</div>

	
</div>
@include('partials._scripts')
@endsection
