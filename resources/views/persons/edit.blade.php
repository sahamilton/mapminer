@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Edit a Manager ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		Edit Manager

		
	</h3>
</div>

<!-- Tabs -->

<?php $buttonLabel ='Edit Person';?>
{{Form::model($person, ['method'=>'PATCH','route'=>['person.update', $person->id]]) }}
@include('persons/partials/_form')
{{Form::close()}}

@stop