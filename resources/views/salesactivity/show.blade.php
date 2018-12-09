@extends ('site.layouts.default')

@section('content')<div class="container">
	<h1>{{$activity->title}}</h1>
	<h4>From {{$activity->datefrom->format('M j Y')}} to {{$activity->dateto->format('M j Y')}}</h4>

	<a href="{{route('salescampaigns')}}">

	<i class="far fa-calendar" aria-hidden="true"></i>
	Back to all campaigns</a>

	<ul class="nav nav-tabs">
		<li class="nav-item ">
			<a class="nav-link active" data-toggle="tab" href="#campaign">Campaign</a>
		</li>
		<li class="nav-item">
			<a class="nav-link"  data-toggle="tab" href="#resources">Resources</a>
		</li>
		
		<li class="nav-item">
			<a class="nav-link"  data-toggle="tab" href="#locations">Locations List ({{$locations->count()}})</a>
		</li>
	
		
		<li class="nav-item">
			<a class="nav-link"  data-toggle="tab" href="#leads">Leads List ({{$leads->count()}})</a>
		</li>

		
	</ul>

	<div class="tab-content">
		<div id="campaign" class="tab-pane fade show active">

			@include('salesactivity.partials._tabcampaign')

		</div>


		<div id="resources" class="tab-pane fade">

			@include('salesactivity.partials._tabresources')
		</div>
		@if($locations)
		<div id="locations" class="tab-pane fade">
			@include('salesactivity.partials._tablocations')
		</div>
		@endif

		@if($leads)
		<div id="leads" class="tab-pane fade">
			@include('salesactivity.partials._tableads')
		</div>
		@endif
	</div>
</div>
@include('partials._scripts')

@endsection
