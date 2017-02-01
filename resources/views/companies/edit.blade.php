@extends('site/layouts/default')

{{-- Page title --}}
@section('title')
Edit National Account 
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		Edit National Account

		<div class="pull-right">
			<a href="{{ route('company.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>



<?php $buttonLabel = 'Edit Company';
?>
{{Form::model($company, ['method'=>'PATCH','route'=>['company.update', $company[0]->id]]) }}
	@include('companies/partials/_form')
{{Form::close()}}
</div>
@stop