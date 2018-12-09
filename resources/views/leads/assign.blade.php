@extends('admin.layouts.default')
@section('content')

	<h2>Assign {{$lead->businessname}} Prospect</h2>
	<p><a href="{{route('leads.show',$lead->id)}}">Return to prospects</a></p>
	<ul class="nav nav-tabs">

		<li class="nav-item "><a class="nav-link active" data-toggle="tab" href="#team"><strong>Sales Team</strong></a></li>

		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#branches"><strong>Branches</strong></a></li>

	</ul>

	<div class="tab-content">
		<div id="team" class="tab-pane fade show active">

			@include('leads.partials._repslist')
		</div>

		<div id="branches" class="tab-pane fade in">
			@include('leads.partials._branchlist')
		</div>
	</div>

@include('partials._scripts')
@endsection