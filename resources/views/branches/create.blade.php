@extends('site/layouts/default')

{{-- Page title --}}
@section('title')
Create a New Branch
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		Create a New Branch

		<div class="pull-right">
			<a href="{{ route('branches.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>

<!-- Tabs -->

<?php $buttonLabel = 'Create Branch';?>
<form method="post" name="createbranch" action ="{{route('branches.store')}}" >
{{csrf_field()}}
	@include('branches/partials/_form')

</form>
@stop