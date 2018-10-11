@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Edit a Service Line::
@parent
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
@section('content')
<div class="page-header">
	<h3>
		Edit Service Line

		<div class="pull-right">
			<a href="{{ route('serviceline.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>

<!-- Tabs -->

<?php $buttonLabel = 'Edit Service Line';?>
{{Form::model($serviceline, ['method'=>'PATCH','route'=>['serviceline.update', $serviceline->id]]) }}

	@include('servicelines.partials._form')
{{Form::close()}}
</div>
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
