@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Create Feedback::
@parent
@endsection
@section('content')
<div class="page-header">
	<h3>
		Create a New Feedback

		<div class="float-right">
			<a href="{{ route('feedback.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>

<!-- Tabs -->

<?php $buttonLabel = 'Create Service Line';?>
<form method="post" action="{{route('feedback.store')}}" name="createFeedback" >
	@csrf

	@include('feedback.partials._feedbackform')
	<input type="submit" name="submit" value="Create Feedback" class="btn btn-info" />
</form>
</div>
@endsection
