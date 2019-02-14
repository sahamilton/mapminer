@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Edit Feedback::
@parent
@endsection
@section('content')
<div class="page-header">
	<h3>
		Edit Feedback

		<div class="float-right">
			<a href="{{ route('feedback.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>

<!-- Tabs -->

<?php $buttonLabel = 'Edit Feedback';?>
<form method="post" action="{{route('feedback.update',$feedback->id)}}" name="editFeedback" >
	@csrf
	@method('put')

	@include('feedback.partials._feedbackform')
	<input type="submit" name="submit" value="Edit Feedback" class="btn btn-info" />
</form>
</form>
</div>
@endsection
