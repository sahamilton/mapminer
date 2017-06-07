@extends ('site.layouts.default')

@section('content')<div class="container">
	<h1>{{$activity->title}}</h1>
	<h4>From {{$activity->datefrom->format('M j Y')}} to {{$activity->dateto->format('M j Y')}}</h4>
	<a href="{{route('salescampaigns')}}">
	<span class='glyphicon glyphicon-calendar'></span>
	Back to all campaigns</a>

	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#campaign">Campaign</a></li>
		<li><a data-toggle="tab" href="#resources">Resources</a></li>
		@if(count($locations)>0)
		<li><a data-toggle="tab" href="#locations">Locations List ({{count($locations)}})</a></li>
		@endif
		@if(count($leads)>0)
		<li><a data-toggle="tab" href="#leads">Leads List ({{count($leads)}})</a></li>
		@endif
	</ul>

	<div class="tab-content">
		<div id="campaign" class="tab-pane fade in active">

			@include('salesactivity.partials._tabcampaign')

		</div>


		<div id="resources" class="tab-pane fade">

			@include('salesactivity.partials._tabresources')
		</div>
		@if(count($locations) > 0)
		<div id="locations" class="tab-pane fade">
			@include('salesactivity.partials._tablocations')
		</div>
		@endif

		@if(count($leads) > 0)
		<div id="leads" class="tab-pane fade">
			@include('salesactivity.partials._tableads')
		</div>
		@endif
	</div>
</div>
@include('partials._scripts')

@endsection
