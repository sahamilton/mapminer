@extends('site/layouts/default')

{{-- Page title --}}
@section('title')
Create a National Account ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		Create a New National Account

		<div class="pull-right">
			<a href="{{ route('company.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>

<!-- Tabs -->

<?php $buttonLabel = 'Create Company';?>
{{Form::open(['route'=>'company.store'])}}
	@include('companies.partials._form')
{{Form::close()}}
</div>
@stop