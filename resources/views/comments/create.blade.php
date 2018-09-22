@extends('site/layouts/default')
@section('content')

<h2>New Feedback</h2>
<?php $buttonLabel = 'New Feedback';?>
{{Form::open(['route'=>'comment.store'])}}
	@include('comments/partials/_form')
{{Form::close()}}
@stop