@extends ('site.layouts.default')

@section('content')

<div class="container">
	<h1>{{$activity->title}}</h1>
	<h4>From {{$activity->datefrom->format('M j Y')}} to {{$activity->dateto->format('M j Y')}}</h4>
	<a href="{{route('salescampaigns')}}">
	<span class='glyphicon glyphicon-calendar'></span>
	Back to all campaigns</a>

	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#home">Campaign</a></li>
		<li><a data-toggle="tab" href="#menu1">Resources</a></li>
		<li><a data-toggle="tab" href="#menu2">Locations List ({{count($locations)}})</a></li>

	</ul>

	<div class="tab-content">
		<div id="home" class="tab-pane fade in active">

			@include('salesactivity.partials._tabcampaign')

		</div>


		<div id="menu1" class="tab-pane fade">

			@include('salesactivity.partials._tabresources')
		</div>
		<div id="menu2" class="tab-pane fade">
			@include('salesactivity.partials._tablocations')
		</div>
	</div>
</div>
@include('partials._scripts')

@endsection
