@extends('site.layouts.default')
@section('content')

<h2>{{$title }} {{$leads->firstname}} {{$leads->lastname}}</h2>


<p><a href="{{route('salesleads.download')}}"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> Download all owned and closed Leads</a></p>

@if($manager)
	<p><a href="{{route('salesleads.index')}}">Return to sales team</a></p>
@endif

<ul class="nav nav-tabs">
	<li class="nav-item ">
		<a class="nav-link active" data-toggle="tab" href="#map">
			<strong>Map View</strong>
		</a>
	</li>
  @if($leads->offeredLeads->count()>0)
  <li class="nav-item">
  	<a class="nav-link" data-toggle="tab" href="#offered">
  		<strong>Offered Leads</strong>
  	</a>
  </li>
  @endif
  <li class="nav-item">
  	<a class="nav-link" data-toggle="tab" href="#owned">
  		<strong>Owned Leads</strong>
  	</a>
  </li>
  

</ul>

<div class="tab-content">
	<div id="map" class="tab-pane fade show active">
		@include('salesleads.partials._tabmapleads')

	</div>
	@if($leads->offeredLeads->count()>0)
	<div id="offered" class="tab-pane fade">
		@include('salesleads.partials._offeredleads')

	</div>
	@endif

	<div id="owned" class="tab-pane fade">
		@include('salesleads.partials._ownedleads')
	</div>
</div>

@include('salesleads.partials._maps')




@include('partials/_scripts')



@endsection
