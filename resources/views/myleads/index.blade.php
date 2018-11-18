@extends('site.layouts.default')
@section('content')

<h1>My Leads</h1>

<div class="row float-right" style="padding-bottom:10px">

	<a href="{{route('myleads.create')}}" class="btn btn-info">
		<i class="fas fa-plus-circle"></i>
			Add A Lead
	</a>
</div>
<div class="row">
	@include('maps.partials._form')
</div>
@include('myleads.partials._tablist')
	
   
@include('partials._scripts')
@endsection
