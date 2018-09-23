@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Create a Service Line::
@parent
@endsection
@section('content')
<div class="page-header">
	<h3>
		Create a New Service Line

		<div class="pull-right">
			<a href="{{ route('serviceline.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>

<!-- Tabs -->

<?php $buttonLabel = 'Create Service Line';?>
{{Form::open(['route'=>'serviceline.store'])}}
	@include('servicelines.partials._form')
{{Form::close()}}
</div>
@endsection