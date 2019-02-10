@extends('site.layouts.default')
@section('content')
<h2>{{$title }} {{$leads->fullName()}}
@if($manager)
and Team
@endif
</h2>

</h2>
<ul class="nav nav-tabs">

	<li class="nav-item ">
		<a class="nav-link active" data-toggle="tab" href="#map">
			<strong>Leads</strong>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link"  data-toggle="tab" href="#team">
			<strong>Team Leads</strong>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#list">
			<strong>My Offered Leads</strong>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#owned">
			<strong>My Owned Leads</strong>
		</a>
	</li>


	

</ul>

<div class="tab-content">
	<div id="map" class="tab-pane fade show active">
		@include('salesleads.partials._tabmapleads')

	</div>
	<div id="team" class="tab-pane fade ">
		@include('salesleads.partials._managerleads')
	</div>
	<div id="list" class="tab-pane fade ">
		@include('salesleads.partials._offeredleads')
	</div>

	
	<div id="owned" class="tab-pane fade">
		@include('salesleads.partials._ownedleads')
	</div>
</div>


@include('salesleads.partials._maps')

@include('partials/_scripts')



@endsection
