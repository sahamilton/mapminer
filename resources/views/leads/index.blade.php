@extends('admin.layouts.default')
@section('content')

<h1>Prospects</h1>



<ul class="nav nav-tabs">
<<<<<<< HEAD
	<li class="active"><a data-toggle="tab" href="#map"><strong>Map View</strong></a></li>

	<li><a data-toggle="tab" href="#list"><strong>List</strong></a></li>

	<li><a data-toggle="tab" href="#team"><strong>Sales Team</strong></a></li>
=======
	<li class="nav-item active">
		<a class="nav-link active" data-toggle="tab" href="#map">
			<strong>Map View</strong>
		</a>
	</li>

	<li class="nav-item">
		<a class="nav-link"  data-toggle="tab" href="#list">
			<strong>List</strong>
		</a>
	</li>

	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#team"><strong>Sales Team</strong></a></li>
>>>>>>> development

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
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
