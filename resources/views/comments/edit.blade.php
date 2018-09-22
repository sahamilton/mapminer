@extends('site/layouts/default')
@section('content')

<h2>Edit Comment</h2>
<?php $buttonLabel = 'Edit Comment';?>

{{Form::model($comment,['method'=>'PATCH','route'=>['comment.update', $comment->id]]) }}
<div>
{{Form::label('comment','Feedback:')}}
<div>
{{Form::textarea('comment')}}
{{ $errors->first('comment') }}
</div></div>
<input type="hidden" name="slug" value="{{$comment->relatesTo->slug}}" />
<input type="submit" class="btn btn-info" name="submit" value="Edit Comment" />
{{Form::close()}}
@stop