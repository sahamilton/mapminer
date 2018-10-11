@extends('admin.layouts.default')
@section('content')

	<h2>Assign {{$lead->businessname}} Prospect</h2>
	<p><a href="{{route('leads.show',$lead->id)}}">Return to prospects</a></p>
	<ul class="nav nav-tabs">
<<<<<<< HEAD
		<li class="active"><a data-toggle="tab" href="#team"><strong>Sales Team</strong></a></li>

		<li><a data-toggle="tab" href="#branches"><strong>Branches</strong></a></li>
=======
		<li class="nav-item active"><a class="nav-link active" data-toggle="tab" href="#team"><strong>Sales Team</strong></a></li>

		<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#branches"><strong>Branches</strong></a></li>
>>>>>>> development

	</ul>

	<div class="tab-content">
		<div id="team" class="tab-pane fade in active">

			@include('leads.partials._repslist')
		</div>

		<div id="branches" class="tab-pane fade in">
			@include('leads.partials._branchlist')
		</div>
	</div>

@include('partials._scripts')
@endsection