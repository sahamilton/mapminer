@extends('site/layouts/default')
@section('content')

<h2>Edit Feedback</h2>
<?php $buttonLabel = 'Edit Feedback';?>

{{Form::model($comment,['method'=>'PATCH','route'=>['comment.update', $comment->id]]) }}
	@include('comments/partials/_form')
{{Form::close()}}
@stop